<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/14 16:20
 */

namespace App\Model;


class SysDepartment extends Model
{
    protected $table = 'sys_departments';
    protected $fillable = [
        'department_name',
        'parent_id',
        'role_ids',
        'all_parent_id',
        'remark'
    ];
}