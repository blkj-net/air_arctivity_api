<?php
/**
 * 接口管理
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/18 10:53
 */

namespace App\Controller;


use App\Constants\ApiCode;
use App\Constants\Common;
use App\Model\SysApi;
use Hyperf\HttpServer\Router\DispatcherFactory;

class ApiController extends BaseController
{
    /**
     * 列表
     * User：caogang
     * DateTime：2021/5/18 10:54
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $name = request()->input('name');
        $method = request()->input('method');
        $where = [];
        if ($name) {
            $where[] = ['name', 'like', "%{$name}%"];
        }
        if ($method) {
            $where[] = ['method', '=', $method];
        }
        $list = SysApi::query()->orderBy('api_group', 'asc')->where($where)->paginate($size, ['*'], 'page', $page);
        return $this->success($list->toArray());
    }

    /**
     * 获取所有接口
     * User：caogang
     * DateTime：2021/5/18 11:05s
     */
    public function select()
    {
        $list = SysApi::query()->select(['id', 'key', 'name', 'path', 'description'])->orderBy('api_group', 'asc')->get()->toArray();
        return $this->success($list);
    }

    protected $except = [
        '/', '/test/list', '/routes', '/login', 'manage/logout', 'manage/menu/render_list'
    ];

    /**
     * 获取所有的路由
     * User：caogang
     * DateTime：2021/5/21 9:03
     */
    public function getRoutes()
    {
        $factory = $this->container->get(DispatcherFactory::class);
        $router = $factory->getRouter('http');
        [$routes, $test] = $router->getData();
        $routeLists = [];
        foreach ($routes as $k => $v) {
            foreach ($v as $kk => $vv) {
                if (!in_array($kk, $this->except)) {
                    $data = [
                        'method' => $k,
                        'route'  => trim($kk, '/')
                    ];
                    $routeLists[] = $data;
                }
            }
        }
        foreach ($routeLists as $v) {
            $key = substr($v['route'], strripos($v['route'], "/") + 1);
            $r = explode('/', $v['route']);
            $data = [
                'key'       => $key,
                'method'    => $v['method'],
                'name'      => isset(ApiCode::API_ROUTE[$key]) ? ApiCode::API_ROUTE[$key] : "",
                'path'      => $v['route'],
                'api_group' => isset($r[1]) ? $r[1] : ""
            ];
            SysApi::updateOrCreate(['path' => $data['path']], $data);
        }
        return $routeLists;
    }
}