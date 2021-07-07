<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/31 9:56
 */

namespace App\Service\Front;


use App\Constants\OrderStatus;
use App\Exception\BusinessException;
use App\Exception\PaymentException;
use App\Model\FlightHotel;
use App\Model\FlightOrder;
use App\Model\FlightOrderItem;
use App\Model\FlightStock;
use App\Service\FlightStockService;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class StockService extends BaseService
{

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @Inject
     * @var FlightStockService
     */
    protected $flightStockService;

    public function __construct(LoggerFactory $loggerFactory)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('log', 'order');
    }

    /**
     * 首页列表
     * User：caogang
     * DateTime：2021/5/31 14:07
     * @param $page
     * @param $size
     * @param $field
     * @param array $where
     * @return array
     */
    public function getList($page, $size, $field, array $where = [])
    {
        $date = Carbon::now();
        $page = ($page - 1) * $size;
        $sql = "SELECT {$field} FROM
        (SELECT * FROM flight_stock where stock>0 and sale_end_time>'{$date}' and sale_start_time < '{$date}' HAVING 1 ORDER BY price asc) as b
        GROUP BY start_airports_code,end_airports_code ORDER BY start_time asc Limit {$page},{$size}";
        $list = DB::select($sql);
        $sql = "SELECT count(*) FROM
        (SELECT * FROM flight_stock where stock>0 and sale_end_time>'{$date}' and sale_start_time < '{$date}' HAVING 1 ORDER BY price asc) as b
        GROUP BY start_airports_code,end_airports_code ORDER BY start_time";
        $total = DB::select($sql);
        $total = count($total);
        return [$list, $total];
    }

    /**
     * 详情页列表
     * User：caogang
     * DateTime：2021/5/31 14:08
     * @param $id
     * @return array
     */
    public function detailsList($id)
    {
        $stock = FlightStock::query()->find($id);
        $list = FlightStock::where('start_airports_code', $stock->start_airports_code)
            ->where('end_airports_code', $stock->end_airports_code)
            ->where('sale_end_time', '>', Carbon::now())
            ->orderBy('start_time', 'asc')
            ->get()->toArray();
        $arr = [];
        foreach ($list as $index => &$item) {
            $item['diff_time'] = diff_time($item['start_time'], $item['end_time']);
            $date = Carbon::parse($item['start_time'])->format('Y-m-d');
            $arr[$date][] = $item;
        }
        $lists = [];
        foreach ($arr as $k => &$v) {
            $checked = false;
            if (in_array($id, array_column($v, 'id'))) {
                $checked = true;
            }
            // 获取所有价格
            $prices = array_column($v, 'price');
            // 根据价格排序
            array_multisort($prices, SORT_ASC, $v);
            $a = [
                'date'      => $k,
                'week'      => $v[0]['schedule'],
                'mix_price' => min($prices),
                'checked'   => $checked,
                'item'      => $v
            ];
            $lists[] = $a;
        }
        return $lists;
    }

    /**
     * 创建订单
     * User：caogang
     * DateTime：2021/5/31 14:08
     */
    public function createOrder($id, array $params)
    {
        $userInfo = $params['user_info'];
        // 判断是否存在未支付的订单
        $where = [
            ['uid', '=', $params['uid']],
            ['status', '=', OrderStatus::ORDER_PAY_WAIT],
        ];
        $order = FlightOrder::query()->where($where)->first();
        if ($order) {
            throw new BusinessException(10000, "您存在尚未支付的订单，请支付或取消后再次提交");
        }
        $lock = redis_lock(self::STOCK_REDIS_PREFIX . $id);
        if ($lock) {
            DB::beginTransaction();
            try {
                // 从redis中获取库存
                $redisStock = redis('stock')->get(self::STOCK_REDIS_PREFIX . $id);
                if (!$redisStock) {
                    $redisStock = FlightStock::query()->where('id', $id)->value('stock');
                    redis('stock')->set(self::STOCK_REDIS_PREFIX . $id, $redisStock);
                }
                $stockInfo = FlightStock::find($id);
                $number = count($userInfo);
                if ($redisStock < $number) {
                    throw new BusinessException(10000, '库存不足');
                }
                // 库存小于等于 库存限制数量，只能一张一张购买
                if ($redisStock <= $stockInfo->limit_nums && $number > 1) {
                    throw new BusinessException(10000, '该航班限制只能订购一张');
                }
                // 酒店信息
                $hotel = FlightHotel::query()->find($params['hotel_id']);
                if (!$hotel) {
                    throw new BusinessException(10000, '所选酒店不存在');
                }
                // 总价 机票价格+酒店价格
                $total_amount = bcadd($stockInfo->price * $number, $hotel->price, 2);
                $order = [
                    'order_no'        => createOrderNo(),
                    'hotel_id'        => $params['hotel_id'],
                    'flight_stock_id' => $id,
                    'flight_name'     => $stockInfo->flight_name,
                    'number'          => $number,
                    'username'        => $params['username'],
                    'idcard_no'       => $params['idcard_no'],
                    'phone'           => $params['phone'],
                    'total_amount'    => $total_amount,
                    'flight_amount'   => $stockInfo->price * $number,
                    'hotel_amount'    => $hotel->price,
                    'uid'             => $params['uid']
                ];
                $orderId = FlightOrder::create($order)->id;
                foreach ($userInfo as $v) {
                    $item = [
                        'flight_stock_id' => $id,
                        'order_id'        => $orderId,
                        'username'        => $v['username'],
                        'idcard_no'       => $v['idcard_no'],
                        'phone'           => $v['phone'],
                        'price'           => $stockInfo->price
                    ];
                    // 判断该身份证号码是否已经订购了该航班
                    $where = [
                        ['flight_stock_id', '=', $id],
                        ['idcard_no', '=', $v['idcard_no']]
                    ];
                    $exists = FlightOrderItem::where($where)->exists();
                    if ($exists) {
                        throw new BusinessException(10000, '身份证：' . $v['idcard_no'] . '，已经订购了该航班');
                    }
                    FlightOrderItem::create($item);
                }
                // 减库存
                $stockInfo->stock = $stockInfo->stock - $number;
                $ret = $stockInfo->save();
                if ($ret) {
                    redis('stock')->decrBy(self::STOCK_REDIS_PREFIX . $id, $number);
                }
                redis_unlock($lock);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                redis_unlock($lock);
                throw new BusinessException(10000, $exception->getMessage());
            }
            $this->logger->info("【创建订单】用户：{$params['uid']}创建订单成功，订单号为：{$order['order_no']}");
            return ['order_no' => $order['order_no']];
        } else {
            return false;
        }
    }

    /**
     * 订单信息
     */
    public function orderInfo(string $order_no)
    {
        $details = FlightOrder::with(['item:id,order_id,flight_stock_id,username,idcard_no,phone,price', 'stock:id,start_city,end_city,start_time,end_time'])
            ->where('order_no', $order_no)
            ->first();
        $week = Carbon::parse($details['stock']['start_time'])->dayOfWeek == 0 ? 7 : Carbon::parse($details['stock']['start_time'])->dayOfWeek;
        $details['stock']['week'] = $week;
        // 订单过期时间
        $diffTime = Carbon::now()->diffInSeconds($details->created_at);
        if ($diffTime > OrderStatus::ORDER_EXPIRE_TIME) {
            $details['expire_time'] = 0;
        } else {
            $details['expire_time'] = OrderStatus::ORDER_EXPIRE_TIME - $diffTime;
        }

        if (!$details) {
            throw new PaymentException('订单信息不存在');
        }
        return $details->toArray();

    }

    /**
     * 待支付的订单
     * User：caogang
     * DateTime：2021/6/24 11:23
     * @param $uid
     * @return array|false
     */
    public function waitPayOrder($uid)
    {
        $order = FlightOrder::query()->where([
            'uid'    => $uid,
            'status' => OrderStatus::ORDER_PAY_WAIT
        ])->first();
        if ($order) {
            return ['order_no' => $order->order_no];
        } else {
            return false;
        }
    }

    public function getOrderList($page, $size, $where = [], $field = ['*'])
    {
        $orderList = FlightOrder::query()
            ->with("stock:id,start_city,end_city,start_time")->where($where)
            ->orderBy('id', 'desc')
            ->paginate($size, $field, 'page', $page)->toArray();
        $list = [];
        foreach ($orderList['data'] as $key => $order) {
            $list[$key]['id'] = $order['id'];
            $list[$key]['order_no'] = $order['order_no'];
            $list[$key]['uid'] = $order['uid'];
            $list[$key]['total_amount'] = $order['total_amount'];
            $list[$key]['refund_amount'] = $order['refund_amount'];
            $list[$key]['flight_name'] = $order['flight_name'];
            $list[$key]['start_city'] = $order['stock']['start_city'];
            $list[$key]['end_city'] = $order['stock']['end_city'];
            $list[$key]['start_time'] = Carbon::parse($order['stock']['start_time'])->format('m-d H:i');
            $list[$key]['status'] = $order['status'];
            $list[$key]['status_str'] = OrderStatus::getMessage($order['status']);
            // 获取酒店名称
            $hotelName = FlightHotel::query()->where('id', $order['hotel_id'])->value('name');
            $list[$key]['hotel_name'] = $hotelName ?? "";
        }
        $orderList['data'] = $list;
        return $orderList;
    }

    /**
     * 取消订单
     * User：caogang
     * DateTime：2021/6/28 17:46
     */
    public function orderCancel($order_no)
    {
        $order = FlightOrder::query()->where('order_no', $order_no)->first();
        if (!$order) {
            throw new BusinessException(10000, '订单不存在');
        }
        if ($order->status != OrderStatus::ORDER_PAY_WAIT) {
            throw new BusinessException(10000, '只能待支付的订单才能取消操作');
        }

        DB::beginTransaction();
        try {
            // 1.修改状态为取消
            $order->status = OrderStatus::ORDER_CANCEL;
            // 2.加库存
            $this->flightStockService->addOrSubStock($order->flight_stock_id, 'add', $order->number);
            $order->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->logger->error("订单：{$order->order_no},取消操作失败，原因：" . $exception->getMessage());
            return false;
        }
        return true;
    }

    /**
     * 退款操作
     * User：caogang
     * DateTime：2021/6/29 15:22
     * @param $order_no
     * @return bool
     */
    public function orderRefund($order_no)
    {
        $order = FlightOrder::query()->where('order_no', $order_no)->first();
        if (!$order) {
            throw new BusinessException(10000, '订单不存在');
        }
        // 查询航班的起飞时间
        // 判断起飞时间与当前时间的比对  起飞前10小时不能发起退款
        $stock = FlightStock::query()->find($order->flight_stock_id);
        $tikeOfTime = $stock->start_time;
        $diffTime = Carbon::parse()->diffInHours($tikeOfTime, false);
        if ($diffTime < 10) {
            throw new BusinessException(10000, '距离航班起飞不足10小时了，不能发起退款');
        }
        if ($order->status != OrderStatus::ORDER_PAY_COMPLETE && $order->status != OrderStatus::ORDER_COMPLETE) {
            throw new BusinessException(10000, '只有支付成功或者已完成的订单才能退款操作');
        }
        $order->status = OrderStatus::ORDER_REFUND_ING;
        return $order->save();
    }
}