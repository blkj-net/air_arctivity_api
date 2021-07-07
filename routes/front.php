<?php
declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::get('/stock/list', [\App\Controller\Front\StockController::class, 'homeList']);
Router::get('/stock/details', [\App\Controller\Front\StockController::class, 'detailsList']);
Router::post('/stock/create_order', [\App\Controller\Front\StockController::class, 'createOrder']);

Router::get('/stock/hotel', [\App\Controller\Front\StockController::class, 'getHotel']);

Router::get('/order/info', [\App\Controller\Front\StockController::class, 'orderInfo']);

Router::get('/order/list', [\App\Controller\Front\StockController::class, 'orderList']);

// 取消订单
Router::post('/order/cancel', [\App\Controller\Front\StockController::class, 'orderCancel']);
// 订单退款
Router::post('/order/refund', [\App\Controller\Front\StockController::class, 'orderRefund']);

// 查询是否存在未支付的订单
Router::get('/order/wait_pay', [\App\Controller\Front\StockController::class, 'waitPayOrder']);

Router::get('/wechat/oauth', [\App\Controller\Front\WechatController::class, 'oauth']);
Router::post('/wechat/pay', [\App\Controller\Front\WechatController::class, 'pay']);
Router::addRoute(['GET', "POST"], '/wechat/pay/callback', [\App\Controller\Front\WechatController::class, 'callback']);
Router::addRoute(['GET', "POST"], '/wechat/refund/callback', [\App\Controller\Front\WechatController::class, 'refundCallback']);
