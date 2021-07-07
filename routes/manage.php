<?php
declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

// 退出系统
Router::post('/logout', [\App\Controller\UserController::class, 'logout']);

// 用户模块
Router::addGroup('/user', function () {
    Router::get('/list', [\App\Controller\UserController::class, 'list']);
    Router::post('/create', [\App\Controller\UserController::class, 'create']);
    Router::post('/disable', [\App\Controller\UserController::class, 'disable']);
    Router::post('/setting_role', [\App\Controller\UserController::class, 'settingRole']);
    Router::put('/update', [\App\Controller\UserController::class, 'update']);
    Router::get('/info', [\App\Controller\UserController::class, 'info']);
    Router::delete('/delete', [\App\Controller\UserController::class, 'delete']);
});

// 部门管理模块
Router::addGroup('/department', function () {
    Router::get('/select', [\App\Controller\DepartmentController::class, 'select']);
    Router::get('/list', [\App\Controller\DepartmentController::class, 'list']);
    Router::post('/create', [\App\Controller\DepartmentController::class, 'create']);
    Router::put('/update', [\App\Controller\DepartmentController::class, 'update']);
    Router::delete('/delete', [\App\Controller\DepartmentController::class, 'delete']);
});

// 菜单模块
Router::addGroup('/menu', function () {
    Router::get('/list', [\App\Controller\MenuController::class, 'getList']);
    Router::post('/create', [\App\Controller\MenuController::class, 'create']);
    Router::put('/update', [\App\Controller\MenuController::class, 'update']);
    Router::delete('/delete', [\App\Controller\MenuController::class, 'delete']);
    Router::get('/render_list', [\App\Controller\MenuController::class, 'renderMenuList']);
});

// 角色模块
Router::addGroup('/role', function () {
    Router::get('/select', [\App\Controller\RoleController::class, 'select']);
    Router::get('/list', [\App\Controller\RoleController::class, 'list']);
    Router::post('/create', [\App\Controller\RoleController::class, 'create']);
    Router::put('/update', [\App\Controller\RoleController::class, 'update']);
    Router::delete('/delete', [\App\Controller\RoleController::class, 'delete']);
    Router::delete('/batch_del', [\App\Controller\RoleController::class, 'batchDelete']);
});

/**
 * 接口管理
 */
Router::addGroup('/api', function () {
    Router::get('/list', [\App\Controller\ApiController::class, 'list']);
    Router::get('/select', [\App\Controller\ApiController::class, 'select']);
});

// 权限模块
Router::addGroup('/permisson', function () {
    // 角色权限列表
    Router::get('/role_list', [\App\Controller\PermissionController::class, 'roleList']);
    // 分配角色权限
    Router::post('/role_auth', [\App\Controller\PermissionController::class, 'roleAuth']);
    // 部门权限列表
    Router::get('/department_list', [\App\Controller\PermissionController::class, 'departmentList']);
    // 分配部门权限
    Router::post('/department_auth', [\App\Controller\PermissionController::class, 'departmentAuth']);
});


// 日志列表
Router::get('/log/list', [\App\Controller\SystemLogRecordController::class, 'list']);

// 系统配置管理
Router::addGroup('/configure', function () {
    Router::get('/list', [\App\Controller\SystemConfigController::class, 'list']);
    Router::post('/create', [\App\Controller\SystemConfigController::class, 'create']);
    Router::put('/update', [\App\Controller\SystemConfigController::class, 'update']);
    Router::delete('/delete', [\App\Controller\SystemConfigController::class, 'delete']);
});

// 航班库存相关
Router::addGroup('/flight', function () {

    Router::get('/city_name', [\App\Controller\FlightInfoController::class, 'getCityName']);
    Router::get('/airports_list', [\App\Controller\FlightInfoController::class, 'airportsList']);

    // 航班信息
    Router::addGroup('/info', function () {
        Router::get('/list', [\App\Controller\FlightInfoController::class, 'list']);
        Router::get('/select', [\App\Controller\FlightInfoController::class, 'select']);
        Router::get('/info', [\App\Controller\FlightInfoController::class, 'info']);
        Router::post('/create', [\App\Controller\FlightInfoController::class, 'create']);
        Router::put('/update', [\App\Controller\FlightInfoController::class, 'update']);
        Router::delete('/delete', [\App\Controller\FlightInfoController::class, 'delete']);
        Router::delete('/batch_del', [\App\Controller\FlightInfoController::class, 'batchDelete']);
        Router::post('/import', [\App\Controller\FlightInfoController::class, 'import']);
        Router::get('/excel/download', [\App\Controller\FlightInfoController::class, 'download']);
    });
    // 航班配置
    Router::addGroup('/config', function () {
        Router::get('/list', [\App\Controller\FlightConfigController::class, 'list']);
        Router::post('/create', [\App\Controller\FlightConfigController::class, 'create']);
        Router::put('/update', [\App\Controller\FlightConfigController::class, 'update']);
        Router::delete('/delete', [\App\Controller\FlightConfigController::class, 'delete']);
        Router::delete('/batch_del', [\App\Controller\FlightConfigController::class, 'batchDelete']);
        Router::post('/create_stock', [\App\Controller\FlightConfigController::class, 'createStock']);
    });

    // 库存信息
    Router::addGroup('/stock', function () {
        Router::get('/list', [\App\Controller\FlightStockController::class, 'list']);
        Router::put('/sub_stock', [\App\Controller\FlightStockController::class, 'subStock']);
        Router::put('/update', [\App\Controller\FlightStockController::class, 'update']);
    });

    // 订单信息
    Router::addGroup('/order', function () {
        Router::get('/list', [\App\Controller\FlightOrderController::class, 'list']);
        Router::get('/info', [\App\Controller\FlightOrderController::class, 'info']);
        Router::put('/lock', [\App\Controller\FlightOrderController::class, 'lock']);
        Router::put('/update', [\App\Controller\FlightOrderController::class, 'update']);
        Router::post('/audit', [\App\Controller\FlightOrderController::class, 'audit']);
    });

    // 酒店信息
    Router::addGroup('/hotel', function () {
        Router::get('/list', [\App\Controller\FlightHotelController::class, 'list']);
        Router::post('/create', [\App\Controller\FlightHotelController::class, 'create']);
        Router::put('/update', [\App\Controller\FlightHotelController::class, 'update']);
        Router::delete('/delete', [\App\Controller\FlightHotelController::class, 'delete']);
        Router::delete('/batch_del', [\App\Controller\FlightHotelController::class, 'batchDelete']);
        Router::post('/upload', [\App\Controller\FlightHotelController::class, 'upload']);
        Router::get('/del_img', [\App\Controller\FlightHotelController::class, 'delImg']);
        Router::get('/air_list', [\App\Controller\FlightHotelController::class, 'flightList']);
    });
});