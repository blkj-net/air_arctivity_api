<?php
/**
 * 航班信息模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 15:19
 */

namespace App\Model;

class FlightInfo extends Model
{
    protected $table = 'flight_info';
    protected $fillable = [
        'flight_name',
        'start_airports_code',
        'end_airports_code',
        'start_city_name',
        'end_city_name',
        'start_time',
        'end_time',
        'price',
        'stock',
        'limit_nums'
    ];
}