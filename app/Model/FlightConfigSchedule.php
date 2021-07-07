<?php
/**
 * 航班配置模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 15:19
 */

namespace App\Model;

class FlightConfigSchedule extends Model
{
    protected $table = 'flight_config_schedule';
    public $timestamps = false;
    protected $fillable = [
        'config_id',
        'schedule',
        'traveller_type',
        'original_price',
        'price',
        'discount_amount',
    ];
}