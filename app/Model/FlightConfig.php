<?php
/**
 * 航班配置模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 15:19
 */

namespace App\Model;

class FlightConfig extends Model
{
    protected $table = 'flight_config';
    protected $fillable = [
        'flight_info_id',
        'traveller_type',
        'sale_start_time',
        'sale_end_time',
        'flight_hours_ago',
        'remark'
    ];

    public function info()
    {
        return $this->belongsTo(FlightInfo::class, 'flight_info_id', 'id');
    }

    public function scheduleList()
    {
        return $this->hasMany(FlightConfigSchedule::class, 'config_id', 'id');
    }
}