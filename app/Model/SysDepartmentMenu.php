<?php
/**
 * 部门、菜单中间表模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 14:08
 */

namespace App\Model;


class SysDepartmentMenu extends Model
{
    protected $table = 'sys_department_menus';
    protected $fillable = [
        'department_id',
        'menu_id',
        'ability'
    ];
    public $timestamps = false;
}