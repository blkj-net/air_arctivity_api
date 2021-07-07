<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Common;
use App\Exception\BusinessException;
use App\Service\PermissionService;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 权限验证中间件
 * Class PermissionMiddleware
 * @package App\Middleware
 */
class PermissionMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $except = [
        'manage/logout',
        'manage/user/info',
        'manage/menu/render_list'
    ];

    /**
     * @Inject
     * @var PermissionService
     */
    protected $permissionService;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = request()->path();
        if (request()->userInfo['id'] != Common::ADMIN_ID && !in_array($path, $this->except)) {
            // 获取角色菜单API
            $roleRoutes = $this->permissionService->roleAuthMenuList(request()->userInfo['role_ids']);
            p($path, '当前路由');
            p($roleRoutes, '有权限的路由');
            // 获取部门菜单绑定的API
            $departmentRoutes = $this->permissionService->departmentAuthMenuList(request()->userInfo['department_id']);
            p($departmentRoutes);
            if (!in_array($path, $roleRoutes) && !in_array($path, $departmentRoutes)) {
                throw new BusinessException(403, "您暂无权限访问");
            }
        }
        return $handler->handle($request);
    }
}