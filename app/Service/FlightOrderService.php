<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/31 16:12
 */

namespace App\Service;

use AndPHP\HyperfSign\Annotation\Sign;
use App\Constants\Common;
use App\Constants\OrderStatus;
use App\Exception\BusinessException;
use App\Exception\PaymentException;
use App\Libs\Sms\WisdomService;
use App\Model\FlightOrder;
use App\Model\FlightOrderItem;
use App\Model\FlightStock;
use Carbon\Carbon;
use Hyperf\Config\Annotation\Value;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class FlightOrderService extends BaseService
{
    /**
     * @Inject
     * @var FlightStockService
     */
    protected $stockService;

    /**
     * 短信模板信息
     * @Value("sms.issue_ticket_sms_templete")
     */
    protected $smsTemplate;

    /**
     * 短信服务
     * @Inject
     * @var WisdomService
     */
    protected $wisdomService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @Inject
     * @var SystemConfigService
     */
    protected $systemConfigService;

    public function __construct(LoggerFactory $loggerFactory)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('log', 'order');
        parent::__construct();
    }

    /**
     * 获取列表
     * User：caogang
     * DateTime：2021/5/31 17:08
     * @param $page
     * @param $size
     * @param array $where
     * @param string[] $field
     * @return array
     */
    public function getList($page, $size, array $where = [], $field = ['*'])
    {
        $list = FlightOrder::query()
            ->with(['item:id,order_id,flight_stock_id,username,idcard_no,phone,price', 'stock:id,start_city,end_city,start_time,end_time'])
            ->where($where)
            ->orderBy('id', 'desc')
            ->orderBy('status', 'asc')
            ->paginate($size, $field, 'page', $page)
            ->toArray();
        foreach ($list['data'] as &$item) {
            // 取配置退款数据
            $refundAmount = $this->systemConfigService->getValueByKey('refund_amount');
            $item['refund_amount'] = $refundAmount ?? 0;
        }
        return $list;
    }

    /**
     * 订单详情
     * User：caogang
     * DateTime：2021/5/31 16:20
     * @param $id
     */
    public function details($id)
    {
        $details = FlightOrder::with(['item:id,order_id,flight_stock_id,username,idcard_no,phone,price', 'stock:id,start_city,end_city,start_time,end_time'])->find($id);
        if ($details) {
            return $details->toArray();
        } else {
            return [];
        }
    }

    /**
     * 订单锁定、解锁
     * User：caogang
     * DateTime：2021/5/31 17:11
     * @param $id
     */
    public function lock($id, $isLock)
    {
        $isLock = $isLock ? request()->userInfo['id'] : 0;
        // 获取订单
        $order = FlightOrder::find($id);
        if ($isLock == 0) { // 解锁
            // 只有超级管理员或上锁的用户可以操作
            if ($order->is_lock == $isLock || request()->userInfo['id'] == Common::ADMIN_ID) {
                $order->is_lock = $isLock;
            } else {
                throw new BusinessException(10000, '只有超级管理或上锁的员工，才能解锁');
            }
        } else { // 上锁
            $order->is_lock = $isLock;
        }
        return $order->save();
    }

    /**
     * 编辑订单信息
     * User：caogang
     * DateTime：2021/5/31 22:25
     * @param array $params
     * @return bool
     */
    public function update(array $params)
    {
        $order = FlightOrder::query()->with("stock:id,start_city,end_city,start_time,end_time")->find($params['id']);
        if (!$order) {
            throw new BusinessException(10000, '订单不存在');
        }
        // 判断当前订单是否为锁定状态
        if ($order->is_lock && request()->userInfo['id'] != Common::ADMIN_ID && request()->userInfo['id'] != $order->is_lock) {
            throw new BusinessException(10000, '当前订单处于锁定状态,你没有操作权限');
        }
        DB::beginTransaction();
        try {
            // 判断订单状态
            if ($params['status'] == OrderStatus::ORDER_DISCARD) {
                //  订单作废 -------- 加库存
                $this->stockService->incrStock($order->flight_stock_id, $order->number);
            } else {
                $diffNums = $order->number - count($params['user_info']);
                if ($diffNums > 0) { // 减人数
                    // 加预定库存
                    $this->stockService->incrStock($order->flight_stock_id, abs($diffNums));
                    // 订单总数量
                    $order->number = $order->number - abs($diffNums);
                }
                if ($diffNums < 0) { // 加人数
                    // 获取预定库存数量
                    $stockNums = FlightStock::query()->where('id', $order->flight_stock_id)->value('stock');
                    if ($stockNums > abs($diffNums)) {
                        // 减库存
                        $this->stockService->decrStock($order->flight_stock_id, abs($diffNums));
                        // 加订单数量
                        $order->number = $order->number + abs($diffNums);
                    } else {
                        throw new BusinessException(10000, "库存不足,剩余：" . $stockNums);
                    }
                }
                // 如果存在删除子订单
                if (isset($params['del_item_ids'])) {
                    FlightOrderItem::destroy($params['del_item_ids']);
                }
                $totalAmount = 0;
                // 更新用户信息
                foreach ($params['user_info'] as $v) {
                    if (isset($v['id']) && $v['id']) { // 更新
                        FlightOrderItem::where('id', $v['id'])->update(FlightOrderItem::fillableFromArray($v));
                    } else { // 新增
                        FlightOrderItem::updateOrCreate($v);
                    }
                    if ($order->status != OrderStatus::ORDER_COMPLETE && $params['status'] == OrderStatus::ORDER_COMPLETE) {
                        if (!isset($v['ticket_no']) || !$v['ticket_no']) {
                            throw new BusinessException(10000, "票号不能为空，请填写票号");
                        }
                    }
                    $totalAmount += $v['price'];
                }
                $order->total_amount = $totalAmount;
            }
            // 订单完成操作
            if ($params['status'] == OrderStatus::ORDER_COMPLETE) {
                if ($order->status == OrderStatus::ORDER_DISCARD && $params['status'] == OrderStatus::ORDER_COMPLETE) {
                    throw new BusinessException(10000, "当前订单已废弃，不能完成处理操作");
                }
                if ($order->status != OrderStatus::ORDER_COMPLETE) {
                    $order->status = OrderStatus::ORDER_COMPLETE;
                    // 发送短信
                    $smsMsg = str_replace(['username', 'idcardnum', 'startdate', 'startcity', 'endcity', 'strttime', 'endtime', 'flightno'], [
                        $order->username,
                        $order->idcard_no,
                        Carbon::parse($order->stock->start_time)->format("Y-m-d"),
                        $order->stock->start_city,
                        $order->stock->end_city,
                        Carbon::parse($order->stock->start_time)->format("H:i"),
                        Carbon::parse($order->stock->end_time)->format("H:i"),
                        $order->flight_name,
                    ], $this->smsTemplate);
                    $this->wisdomService->send($order->phone, $smsMsg);
                }
            }
            // 订单废弃操作
            if ($params['status'] == OrderStatus::ORDER_DISCARD) {
                if ($order->status == OrderStatus::ORDER_PAY_COMPLETE) {
                    throw new BusinessException(10000, "当前订单已支付，不能作废处理操作");
                }
                if ($order->status == OrderStatus::ORDER_COMPLETE) {
                    throw new BusinessException(10000, "当前订单已完成，不能作废处理操作");
                }
                $order->status = $params['status'];
            }
            $order->is_lock = $params['is_lock'];
            $order->save();
            redis('stock')->del(self::STOCK_REDIS_PREFIX . $order->flight_stock_id);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new BusinessException(10000, $exception->getMessage());
        }
        return true;
    }

    /**
     * 退款审核
     * User：caogang
     * DateTime：2021/6/29 15:28
     */
    public function refundAudit($order_no, $type, $refund_amount)
    {
        $order = FlightOrder::query()->where('order_no', $order_no)->first();
        if (!$order) {
            throw new  BusinessException(10000, '订单不存在');
        }
        DB::beginTransaction();
        try {
            if ($type == 'agree') { // 同意操作
                if ($refund_amount > $order->total_amount) {
                    throw new BusinessException(10000, '退款金额不能大于订单的总金额');
                }
                $order->refund_amount = $refund_amount;
                // 加库存操作
                $this->stockService->addOrSubStock($order->flight_stock_id, 'add', $order->number);
                // 退款打钱给用户
                $this->refund($order);
                $this->logger->info("订单：{$order_no}，退款成功");
            } else if ($type == 'refuse') { // 拒绝操作
                $order->status = OrderStatus::ORDER_REFUND_FAIL;
            } else {
                throw new BusinessException(10000, '操作类型错误');
            }
            $order->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->logger->error("订单：{$order_no}，退款失败，原因：{$exception->getMessage()}");
            return false;
        }
        return true;
    }

    // 退款操作
    public function refund($order)
    {
        $url = config('pay_url') . '/payments/refund';
        $data = [
            'order_sn'          => $order->order_no,
            'refund_amount'     => $order->refund_amount * 100, // 单位，分
            'refund_notify_url' => config('refund_notify_url'),
            'client'            => 0
        ];
        // 参数签名操作
        $data = $this->sign($data);
        $client = new \GuzzleHttp\Client();
        try {
            $ret = $client->post($url, ['form_params' => $data]);
            $result = $ret->getBody()->getContents();
            $ret = json_decode($result, true);
            if ($ret['code'] != 0) {
                throw new PaymentException($ret['msg']);
            }
            $this->logger->info("【订单退款】支付中心，返回数据：{$result}");
        } catch (\Exception $exception) {
            $this->logger->info("订单退款失败，原因：{$exception->getMessage()}");
            throw new PaymentException($exception->getMessage());
        }
        return $result;
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

}