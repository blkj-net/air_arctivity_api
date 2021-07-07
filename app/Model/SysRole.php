<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/14 16:20
 */

namespace App\Model;


class SysRole extends Model
{
    protected $table = 'sys_roles';
    protected $fillable = [
        'role_name',
        'parent_id',
        'default_router',
        'all_parent_id',
        'remark'
    ];
    protected $appends = ['parent_name'];

    public function getParentNameAttribute()
    {
        $name = $this->where('id', $this->parent_id)->value('role_name');
        return $name ?? "";
    }
}