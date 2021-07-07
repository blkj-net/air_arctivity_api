<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/23 10:24
 */

namespace App\Service\Front;


use AndPHP\HyperfSign\Annotation\Sign;
use App\Constants\OrderStatus;
use App\Exception\PaymentException;
use App\Libs\Sms\WisdomService;
use App\Model\FlightOrder;
use App\Model\WechatUser;
use Carbon\Carbon;
use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class WechatService extends BaseService
{

    /**
     * 支付地址
     * @Value("pay_url")
     */
    protected $payUrl;

    /**
     * 回调地址
     * @Value("pay_notify_url")
     */
    protected $payNotifyUrl;

    /**
     * 短信模板信息
     * @Value("sms.pay_sms_templete")
     */
    protected $smsTemplate;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * 短信服务
     * @Inject
     * @var WisdomService
     */
    protected $wisdomService;

    public function __construct(LoggerFactory $loggerFactory)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('log', 'order');
    }

    /**
     * 微信授权
     * User：caogang
     * DateTime：2021/6/23 10:24
     * @param string $code
     */
    public function oauth(string $code)
    {
        // 判断支付中心的地址是否存在
        if (!$this->payUrl) {
            throw new PaymentException('支付服务的地址不存在');
        }
        // 请求第三方服务
        $client = new \GuzzleHttp\Client();
        $url = $this->payUrl . '/wechat/official/oauth';
        try {
            $ret = $client->post($url, ['form_params' => ['code' => $code]]);
            $data = $ret->getBody()->getContents();
            // 微信授权的信息
            $result = json_decode($data, true);
            if ($result['code']) {
                throw new PaymentException($result['msg']);
            }
            $userInfo = $result['data'];
            return $this->saveUserInfo($userInfo);
        } catch (\Exception $exception) {
            throw new PaymentException($exception->getMessage());
        }
    }

    /**
     * 存储用户信息
     * User：caogang
     * DateTime：2021/6/23 11:08
     */
    public function saveUserInfo($data)
    {
        $data['access_token'] = $data['accessToken'];
        $ret = WechatUser::query()->updateOrCreate([
            'openid' => $data['openid']
        ], $data);
        return $ret->toArray();
    }

    /**
     * 订单支付
     * User：caogang
     * DateTime：2021/6/23 11:02
     */
    public function pay($order_no)
    {
        // 查询订单
        $orderInfo = FlightOrder::query()->with(["user:id,openid", "stock:id,start_city,end_city,start_time,end_time"])->where('order_no', $order_no)->first();
        if (!$orderInfo) {
            throw new PaymentException("订单不存在");
        }
        switch ($orderInfo->status) {
            case OrderStatus::ORDER_PAY_COMPLETE:
                throw new PaymentException('该订单已经支付过了');
                break;
            case OrderStatus::ORDER_PAY_EXPIRED:
                throw new PaymentException('该订单已过期');
                break;
            case OrderStatus::ORDER_COMPLETE:
                throw new PaymentException('该订单已完成');
                break;
            case OrderStatus::ORDER_DISCARD:
                throw new PaymentException('该订单已作废');
                break;
        }
        return $this->sendPay($orderInfo->toArray());
    }

    /**
     * 发起支付
     * User：caogang
     * DateTime：2021/6/23 13:22
     */
    public function sendPay(array $params)
    {
        // 判断支付中心的地址是否存在
        if (!$this->payUrl) {
            throw new PaymentException('支付服务的地址不存在');
        }
        if (!$this->payNotifyUrl) {
            throw new PaymentException('支付回调地址不存在');
        }
        // 支付地址
        $url = $this->payUrl . '/payments/pay';
        $data = [
            'order_sn'       => $params['order_no'],
            'desc'           => "航班号：" . $params['flight_name'],
            'money'          => $params['total_amount'] * 100, // 单位，分
            'openid'         => $params['user']['openid'],
            'create_ip'      => request()->getServerParams()['remote_addr'],
            'client'         => 0,
            'pay_notify_url' => $this->payNotifyUrl
        ];
        // 参数签名操作
        $data = $this->sign($data);
        $client = new \GuzzleHttp\Client();
        try {
            $ret = $client->post($url, ['form_params' => $data]);
            $result = $ret->getBody()->getContents();
            $this->logger->info("【订单支付】调起支付成功，返回数据：{$result}");
        } catch (\Exception $exception) {
            $this->logger->info("【订单支付】调起支付失败，原因：{$exception->getMessage()}");
            throw new PaymentException($exception->getMessage());
        }
        return $result;
    }

    /**
     * 支付回调通知
     * User：caogang
     * DateTime：2021/6/23 15:01
     * @param array $params
     */
    public function payNotify(array $params): bool
    {
        $this->logger->info("【支付回调】支付中心返回的数据：" . json_encode($params, JSON_UNESCAPED_UNICODE));
        if ($params['return_code'] == "SUCCESS" && $params['result_code'] == "SUCCESS") {
            // 1.查询订单
            $orderInfo = FlightOrder::query()->with("stock:id,start_city,end_city,start_time,end_time")->where('order_no', $params['out_trade_no'])->first();
            if (!$orderInfo) {
                $this->logger->info("【支付回调】订单不存在，订单号为：{$params['out_trade_no']}");
                return false;
            }
            // 判断当前的订单状态
            if ($orderInfo->status != OrderStatus::ORDER_PAY_WAIT) {
                $this->logger->info("【支付回调】当前订单不是待支付状态，订单号为：{$params['out_trade_no']}");
                return false;
            }
            // 2.修改订单状态
            try {
                $orderInfo->status = OrderStatus::ORDER_PAY_COMPLETE;
                $orderInfo->pay_time = date("Y-m-d H:i:s");
                $orderInfo->save();
                $this->logger->info("【支付回调】修改订单状态为已支付成功，订单号：{$params['out_trade_no']}");
                // 发送短信
                $smsMsg = str_replace(['username', 'idcardnum', 'startdate', 'startcity', 'endcity', 'strttime', 'endtime', 'flightno'], [
                    $orderInfo->username,
                    $orderInfo->idcard_no,
                    Carbon::parse($orderInfo->stock->start_time)->format("Y-m-d"),
                    $orderInfo->stock->start_city,
                    $orderInfo->stock->end_city,
                    Carbon::parse($orderInfo->stock->start_time)->format("H:i"),
                    Carbon::parse($orderInfo->stock->end_time)->format("H:i"),
                    $orderInfo->flight_name,
                ], $this->smsTemplate);
                $this->wisdomService->send($orderInfo->phone, $smsMsg);
            } catch (\Exception $exception) {
                $this->logger->info("【支付回调】修改订单状态为已支付失败，订单号：{$params['out_trade_no']}，原因：{$exception->getMessage()}");
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * 参数签名
     * @Sign("gen")
     * User：caogang
     * DateTime：2021/6/23 11:48
     */
    public function sign($params)
    {
        return $params;
    }

    /**
     * 退款回调通知
     * User：caogang
     * DateTime：2021/6/23 15:01
     * @param array $params
     */
    public function refundNotify(array $params): bool
    {
        $this->logger->info("【退款回调】支付中心返回的数据：" . json_encode($params, JSON_UNESCAPED_UNICODE));
        if ($params['return_code'] == "SUCCESS") {
            // 1.查询订单
            $orderInfo = FlightOrder::query()->with("stock:id,start_city,end_city,start_time,end_time")->where('order_no', $params['out_trade_no'])->first();
            if (!$orderInfo) {
                $this->logger->info("【退款回调】订单不存在，订单号为：{$params['out_trade_no']}");
                return false;
            }
            // 判断当前的订单状态
            if ($orderInfo->status != OrderStatus::ORDER_REFUND_ING) {
                $this->logger->info("【退款回调】当前订单不是退款中状态，订单号为：{$params['out_trade_no']}");
                return false;
            }
            // 2.修改订单状态
            try {
                $orderInfo->status = OrderStatus::ORDER_REFUND;
                $orderInfo->save();
                $this->logger->info("【退款回调】修改订单状态为已退款，订单号：{$params['out_trade_no']}");
            } catch (\Exception $exception) {
                $this->logger->info("【退款回调】修改订单状态为已支付失败，订单号：{$params['out_trade_no']}，原因：{$exception->getMessage()}");
                return false;
            }
        } else {
            return false;
        }
        return true;
    }
}