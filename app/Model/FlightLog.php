<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/1 15:03
 */

namespace App\Model;


class FlightLog extends Model
{
    protected $table = 'flight_log';
    protected $fillable = [
        'user_id',
        'username',
        'route_path',
        'operate_msg',
        'operate_params',
    ];
}