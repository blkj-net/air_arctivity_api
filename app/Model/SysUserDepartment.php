<?php
/**
 * 用户、部门中间表模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 14:08
 */

namespace App\Model;


class SysUserDepartment extends Model
{
    protected $table = 'sys_user_departments';
    protected $fillable = [
        'user_id',
        'department_id',
    ];
    public $timestamps = false;
}