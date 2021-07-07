<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

return [
    'app_name'          => env('APP_NAME', 'skeleton'),
    'app_env'           => env('APP_ENV', 'dev'),
    'scan_cacheable'    => env('SCAN_CACHEABLE', false),
    'pwd_salt'          => env('PWD_SALT', 'qaq'),
    'page'              => 1,
    'size'              => 10,
    'pay_url'           => env("WECHAT_PAY_URL", "http://192.158.0.6:9501"), // 支付中心地址
    'pay_notify_url'    => env("WECHAT_PAY_NOTIFY_URL", "http://admin-west.blkj.net/mock-server/front/wechat/pay/callback"), // 支付回调地址
    'refund_notify_url' => env("WECHAT_REFUND_NOTIFY_URL", "http://admin-west.blkj.net/mock-server/front/wechat/refund/callback"), // 退款回调地址

    StdoutLoggerInterface::class => [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
//            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
];
