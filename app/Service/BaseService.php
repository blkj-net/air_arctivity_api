<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/11 11:08
 */

namespace App\Service;


class BaseService
{
    // 库存redis键值前缀，格式为flight_stock_id:1
    const STOCK_REDIS_PREFIX = "flight_stock_id:";

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {

    }
}