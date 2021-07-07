<?php

use Carbon\Carbon;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\ApplicationContext;
use RedlockHyperf\RedLock;

/**
 * 容器实例
 */
if (!function_exists('container')) {
    function container()
    {
        return ApplicationContext::getContainer();
    }

}

if (!function_exists('redis')) {
    /**
     * Redis
     * @param string $name
     * @return \Hyperf\Redis\RedisProxy|Redis
     */
    function redis($name = 'default')
    {
        return ApplicationContext::getContainer()->get(RedisFactory::class)->get($name);
    }
}

if (!function_exists('redis_lock')) {
    //获取redis锁
    function redis_lock($name, $ttl = 2000)
    {
        return ApplicationContext::getContainer()->get(RedLock::class)->setRedisPoolName()->setRetryCount(1)->lock($name, $ttl);
    }
}
if (!function_exists('redis_unlock')) {
    // 手动释放redis锁
    function redis_unlock($lock)
    {
        return ApplicationContext::getContainer()->get(RedLock::class)->unlock($lock);
    }
}

if (!function_exists('request')) {
    function request()
    {
        return container()->get(RequestInterface::class);
    }
}
if (!function_exists('response')) {
    function response()
    {
        return container()->get(ResponseInterface::class);
    }
}

if (!function_exists('uuid')) {
    function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8)
            . substr($chars, 8, 4)
            . substr($chars, 12, 4)
            . substr($chars, 16, 4)
            . substr($chars, 20, 12);
        return $uuid;
    }
}

//输出控制台日志
if (!function_exists('p')) {
    function p($val, $title = null, $starttime = '')
    {
        print_r('[ ' . date("Y-m-d H:i:s") . ']:');
        if ($title != null) {
            print_r("[" . $title . "]:");
        }
        print_r($val);
        print_r("\r\n");
    }
}

if (!function_exists('createOrderNo')) {
    function createOrderNo()
    {
        $osn = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return $osn;
    }
}

if (!function_exists('diff_time')) {
    function diff_time($start_time, $end_time)
    {
        $start_time = Carbon::parse($start_time);
        $end_time = Carbon::parse($end_time);
        $minutes = $start_time->diffInMinutes($end_time);
        return floor($minutes / 60) . "小时" . ($minutes % 60) . "分钟";
    }
}

/**
 * 校验日期格式是否合法
 * @param string $date
 * @param array $formats
 * @return bool
 */
if (!function_exists('isDateValid')) {
    function isDateValid($date, $formats = array('Y-m-d'))
    {
        $unixTime = strtotime($date);
        if (!$unixTime) { //无法用strtotime转换，说明日期格式非法
            return false;
        }
        //校验日期合法性，只要满足其中一个格式就可以
        foreach ($formats as $format) {
            if (date($format, $unixTime) == $date) {
                return true;
            }
        }
        return false;
    }
}

