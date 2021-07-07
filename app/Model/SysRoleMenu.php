<?php
/**
 * 菜单、角色中间表模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 14:08
 */

namespace App\Model;


class SysRoleMenu extends Model
{
    protected $table = 'sys_role_menus';
    protected $fillable = [
        'role_id',
        'menu_id',
        'ability'
    ];
    public $timestamps = false;
}