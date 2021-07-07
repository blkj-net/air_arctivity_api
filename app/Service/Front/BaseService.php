<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/31 9:56
 */

namespace App\Service\Front;


class BaseService
{
    // 库存redis键值前缀，格式为flight_stock_id:1
    const STOCK_REDIS_PREFIX = "flight_stock_id:";
}