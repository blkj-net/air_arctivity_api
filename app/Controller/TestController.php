<?php

declare(strict_types=1);

/**
 * 测试控制器
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/11 15:03
 */

namespace App\Controller;

use App\Libs\Sms\WisdomService;
use App\Service\FlightOrderService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\Logger\Logger;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;

class TestController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    public function index()
    {
//        $serverParams = request()->getServerParams();
//        return $serverParams;
//        $aa = request()->get();
//        p($aa);
//        $a = MenuApi::getMessage('create');
//        $b = MenuApi::getMessage('update');
//        return ['name' => 'caogang', 'age' => 18];
        return redis('stock')->set('name:1', 1);
    }

    public function test(WisdomService $wisdomService)
    {
//        return $wisdomService->send("18202821916", "测试短信：您好，恭喜您中奖500万");
        return password_hash("bilan123456".config('pwd_salt'),PASSWORD_DEFAULT);
//        $this->logger->info("这是一个测试日志：" . createOrderNo());
//        return 123;
    }

    /**
     * 获取所有的路由
     * User：caogang
     * DateTime：2021/5/21 9:02
     */
    public function getRoutes()
    {
        $factory = $this->container->get(DispatcherFactory::class);
        $router = $factory->getRouter('http');
        $arr = $router->getData();
        return $arr;
    }
}