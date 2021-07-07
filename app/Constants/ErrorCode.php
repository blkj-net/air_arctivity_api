<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    const SERVER_ERROR = 500;

    /**
     * @Message("successful")
     */
    const SUCCESS = 0;
    /**
     * @Message("操作失败")
     */
    const OPERATE_ERROR = 1000;
    /**
     * @Message("添加失败")
     */
    const CREATE_ERROR = 1001;
    /**
     * @Message("删除失败")
     */
    const DELETE_ERROR = 1002;
    /**
     * @Message("更新失败")
     */
    const UPDATE_ERROR = 1003;
    /**
     * @Message("导入失败")
     */
    const IMPORT_ERROR = 1004;
    /**
     * @Message("缺少参数")
     */
    const PARAMS_LOSE = 1005;
    /**
     * @Message("无效的参数")
     */
    const PARAMS_INVALID = 1006;
    /**
     * @Message("名称已存在")
     */
    const NAME_ALREADY_EXISTS = 1007;

    // 用户相关 =======================================================================================================
    /**
     * @Message("用户不存在")
     */
    const USER_NO_EXISTS = 2000;
    /**
     * @Message("密码错误")
     */
    const PASSWORD_ERROR = 2001;
    /**
     * @Message("账号已被禁用，请联系管理员")
     */
    const ACCOUNT_DISABLED = 2002;
    /**
     * @Message("用户已存在")
     */
    const USER_ALREADY_EXISTS = 2003;
    /**
     * @Message("分配角色失败")
     */
    const SETTING_ROLE_FAIL = 2004;

    // 角色相关 =======================================================================================================
    /**
     * @Message("角色名称已存在")
     */
    const ROLE_ALREADY_EXISTS = 2500;
    /**
     * @Message("不能将上级角色设置成下级")
     */
    const ROLE_NODE_FAIL = 2501;
    /**
     * @Message("角色不存在")
     */
    const ROLE_NO_EXISTS = 2502;

    // 部门 =======================================================================================================
    /**
     * @Message("不能将上级部门设置成下级")
     */
    const DEPARTMENT_NODE_FAIL = 2600;
    /**
     * @Message("部门不存在")
     */
    const DEPARTMENT_NO_EXISTS = 2601;

    // 菜单 =======================================================================================================
    /**
     * @Message("不能将上级菜单设置成下级")
     */
    const MENU_NODE_FAIL = 2700;
    /**
     * @Message("存在子级菜单，不能删除")
     */
    const MENU_EXISTS_CHILD = 2701;

}
