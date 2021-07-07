<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/18 15:46
 */
declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class OrderStatus extends AbstractConstants
{
    /**
     * @Message("待支付")
     */
    const ORDER_PAY_WAIT = 1;
    /**
     * @Message("已支付")
     */
    const ORDER_PAY_COMPLETE = 2;
    /**
     * @Message("已过期")
     */
    const ORDER_PAY_EXPIRED = 3;
    /**
     * @Message("已完成")
     */
    const ORDER_COMPLETE = 4;
    /**
     * @Message("已作废")
     */
    const ORDER_DISCARD = 5;
    /**
     * @Message("已取消")
     */
    const ORDER_CANCEL = 6;
    /**
     * @Message("退款中")
     */
    const ORDER_REFUND_ING = 7;
    /**
     * @Message("已退款")
     */
    const ORDER_REFUND = 8;
    /**
     * @Message("拒绝退款")
     */
    const ORDER_REFUND_FAIL = 9;
    /**
     * @Message("后台退款中")
     */
    const ORDER_ADMIN_REFUND_ING = 10;

    /**
     * @Message("订单过期时间15分钟，单位秒")
     */
    const ORDER_EXPIRE_TIME = 15 * 60;
}