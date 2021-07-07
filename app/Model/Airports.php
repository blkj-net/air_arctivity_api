<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/30 11:09
 */

namespace App\Model;


class Airports extends Model
{
    protected $table = 'air_airports';
    protected $fillable = [
        'air_port',
        'air_port_name',
        'air_port_ename',
        'country',
        'country_code',
        'province',
        'chau',
        'chan_chird',
        'city_name',
        'city_ename',
    ];

    /**
     * 根据机场三字码获取城市名称
     * User：caogang
     * DateTime：2021/5/30 11:12
     * @param $airport
     */
    public function getCityByAirport($airport)
    {
        return $this->where('air_port', $airport)->value('city_name');
    }

    public static function getInfoByPort($airport)
    {
        return self::where('air_port', $airport)->first();
    }
}