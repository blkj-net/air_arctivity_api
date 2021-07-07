<?php
/**
 * 航班库存模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 15:19
 */

namespace App\Model;

class FlightStock extends Model
{
    protected $table = 'flight_stock';
    protected $fillable = [
        'start_city',
        'end_city',
        'start_airports_code',
        'end_airports_code',
        'original_price',
        'price',
        'discount_amount',
        'stock',
        'booking_stock',
        'limit_nums',
        'flight_name',
        'start_time',
        'end_time',
        'flight_config_id',
        'flight_info_id',
        'sale_start_time',
        'sale_end_time',
        'remark',
        'schedule'
    ];
}