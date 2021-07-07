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

return [
    'default'               => [
        'handler'   => [
            'class'       => Monolog\Handler\StreamHandler::class,
            'constructor' => [
                'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                'level'  => Monolog\Logger::DEBUG,
            ],
        ],
        'formatter' => [
            'class'       => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format'                => null,
                'dateFormat'            => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
    'order_expired_handdle' => [
        'handler'   => [
            'class'       => Monolog\Handler\StreamHandler::class,
            'constructor' => [
                'stream' => BASE_PATH . '/runtime/logs/order_expired.log',
                'level'  => Monolog\Logger::DEBUG,
            ],
        ],
        'formatter' => [
            'class'       => Monolog\Formatter\LineFormatter::class,
            'constructor' => [
                'format'                => null,
                'dateFormat'            => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true,
            ],
        ],
    ],
    // 订单日志
    'order'                 => [
        'handlers' => [
            [
                'class'       => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => 'php://stdout',
                    'level'  => Monolog\Logger::DEBUG,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => null,
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => BASE_PATH . '/runtime/logs/order.log',
                    'level'  => Monolog\Logger::DEBUG,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => null,
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ]
    ],

    'sms' => [
        'handlers' => [
            [
                'class'       => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => 'php://stdout',
                    'level'  => Monolog\Logger::DEBUG,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => null,
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
            [
                'class'       => Monolog\Handler\StreamHandler::class,
                'constructor' => [
                    'stream' => BASE_PATH . '/runtime/logs/sms.log',
                    'level'  => Monolog\Logger::DEBUG,
                ],
                'formatter'   => [
                    'class'       => Monolog\Formatter\LineFormatter::class,
                    'constructor' => [
                        'format'                => null,
                        'dateFormat'            => 'Y-m-d H:i:s',
                        'allowInlineLineBreaks' => true,
                    ],
                ],
            ],
        ]
    ],

];
