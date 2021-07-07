<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/24 15:34
 */

namespace App\Constants;


class ApiCode
{
    const API_ROUTE = [
        'list'            => '查询',
        'create'          => '添加',
        'update'          => '更新',
        'delete'          => '删除',
        'batch_del'       => '批量删除',
        'info'            => '详情',
        'search'          => '搜索',
        'select'          => '下拉选择',
        'export'          => '导出',
        'import'          => '导入',
        'upload'          => '上传',
        'download'        => '下载',
        'disable'         => '禁用/启用',
        'role_list'       => '角色权限列表',
        'department_list' => '部门权限列表',
        'role_auth'       => '分配角色权限',
        'department_auth' => '分配部门权限',
        'lock'            => '锁定/解锁',
        'create_stock'    => '生成库存',
    ];
}