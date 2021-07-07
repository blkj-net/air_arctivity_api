<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/24 10:14
 */

namespace App\Crontab;


use App\Constants\OrderStatus;
use App\Model\FlightOrder;
use App\Service\FlightOrderService;
use Carbon\Carbon;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Logger\LoggerFactory;

class OrderCrontab
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('log', 'order_expired_handdle');
    }

    /**
     * @Crontab(callback="handdleExpiredOrder", rule="*\/30 * * * * *", memo="处理过期订单")
     */
    public function handdleExpiredOrder()
    {
        $where = [
            ['status', '=', OrderStatus::ORDER_PAY_WAIT],
            ['created_at', '<', Carbon::now()->subSeconds(OrderStatus::ORDER_EXPIRE_TIME)]
        ];
        $orderList = FlightOrder::query()->where($where)->get();
        foreach ($orderList as $order) {
            go(function () use ($order) {
                try {
                    $order->status = OrderStatus::ORDER_PAY_EXPIRED;
                    $order->save();
                } catch (\Exception $exception) {
                    $this->logger->info("订单号：{$order->order_no},过期处理异常，理由：{$exception->getMessage()}");
                }
            });
        }
    }
}