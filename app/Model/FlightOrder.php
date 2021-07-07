<?php

/**
 * 航班订单模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 15:19
 */

namespace App\Model;

use App\Constants\OrderStatus;
use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id
 * @property int $uid 用户ID
 * @property string $order_no 订单号
 * @property int $hotel_id 酒店id
 * @property int $flight_stock_id 库存id
 * @property string $flight_name 航班号
 * @property int $number 购买数量
 * @property string $username 用户姓名
 * @property string $idcard_no 身份证号码
 * @property string $phone 手机号码
 * @property string $total_amount 总价
 * @property string $flight_amount 机票价格
 * @property string $hotel_amount 酒店价格
 * @property int $status 订单状态：1.待支付，2.已支付，3.已过期，4.已完成，5.已作废，6.已取消
 * @property int $is_lock 是否锁定：0否，用户id：表示已锁定
 * @property string $pay_time 支付时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class FlightOrder extends Model
{
    use SoftDeletes;

    protected $table = 'flight_order';
    protected $fillable = ['uid', 'order_no', 'hotel_id', 'flight_stock_id', 'flight_name', 'number', 'username', 'idcard_no', 'phone', 'total_amount', 'flight_amount', 'hotel_amount', 'status', 'pay_time', 'is_lock', 'refund_amount'];
    protected $appends = ['status_str', 'is_lock_str'];

    public function item()
    {
        return $this->hasMany(FlightOrderItem::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(WechatUser::class, 'id', 'uid');
    }

    public function stock()
    {
        return $this->belongsTo(FlightStock::class, 'flight_stock_id', 'id');
    }

    public function getStatusStrAttribute()
    {
        return $this->status == OrderStatus::ORDER_REFUND_ING ? '退款待审核' : OrderStatus::getMessage($this->status);
    }

    public function getisLockStrAttribute()
    {
        return $this->is_lock ? true : false;
    }
}