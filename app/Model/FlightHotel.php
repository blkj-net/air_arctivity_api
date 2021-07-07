<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $air_ports 机场三字码
 * @property string $name 酒店名称
 * @property string $store_name 店铺名称
 * @property string $address 酒店地址
 * @property string $base_info 基础信息，逗号分隔
 * @property string $amenities 便利设施，逗号分隔
 * @property string $images 图片，逗号分隔
 * @property string $introduce 介绍
 * @property string $notice 注意事项
 * @property string $price 价格
 * @property string $discount_amount 优惠价格
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 */
class FlightHotel extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'flight_hotel';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'store_name', 'address', 'base_info', 'amenities', 'images', 'introduce', 'notice', 'price', 'discount_amount'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

}