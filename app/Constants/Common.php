<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/18 15:46
 */
declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class Common extends AbstractConstants
{
    /**
     * @Message("超级管理员ID")
     */
    const ADMIN_ID = 1;
    // 页码
    const PAGE = 1;
    // 页显示数量
    const SIZE = 10;
    /**
     * 顶级角色ID
     */
    const ROLE_PARENT_ID = 0;
    /**
     * 顶级部门ID
     */
    const DEPARTMENT_PARENT_ID = 0;
    /**
     * 顶级菜单ID
     */
    const MENU_PARENT_ID = 0;

    // 系统日志对应等级允许的方法
    const SYSTEM_LOG_ALLOW_METHOD = [
        1 => ['GET', 'POST', 'PUT', 'DELETE'],
        2 => ['POST', 'PUT', 'DELETE'],
        3 => ['PUT', 'DELETE'],
    ];

}