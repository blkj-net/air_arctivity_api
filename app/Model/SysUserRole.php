<?php
/**
 * 用户、角色中间表模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 14:08
 */

namespace App\Model;


class SysUserRole extends Model
{
    protected $table = 'sys_user_roles';
    protected $fillable = [
        'user_id',
        'role_id',
    ];
    public $timestamps = false;
}