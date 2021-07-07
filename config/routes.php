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

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::addGroup('/test', function () {
    Router::get('/list', [\App\Controller\TestController::class, 'index']);
    Router::get('/test', [\App\Controller\TestController::class, 'test']);
});
// 获取所有的路由
Router::get('/routes', [\App\Controller\ApiController::class, 'getRoutes']);
/**
 * ================================ 管理后台 start ======================================
 */
// 登录
Router::post('/login', [\App\Controller\UserController::class, 'login'], ['middleware' => [\App\Middleware\SystemLogMiddleware::class]]);

Router::addGroup('/manage', function () {
    require BASE_PATH . '/routes/manage.php';
}, ['middleware' => [App\Middleware\JWTAuthMiddleware::class, App\Middleware\PermissionMiddleware::class, \App\Middleware\SystemLogMiddleware::class]]);

// 退款回调
Router::get('/wechat/refund/callback', [\App\Controller\FlightOrderController::class, 'refund']);

// 前端
Router::addGroup('/front', function () {
    require BASE_PATH . '/routes/front.php';
});

