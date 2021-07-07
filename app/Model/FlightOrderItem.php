<?php
/**
 * 航班订单详情模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 15:19
 */

namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;

class FlightOrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'flight_order_item';

    protected $fillable = [
        'flight_stock_id',
        'order_id',
        'username',
        'idcard_no',
        'phone',
        'price',
        'ticket_no'
    ];

}