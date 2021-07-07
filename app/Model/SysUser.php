<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * Class SysUser
 * @package App\Models
 * @property int id
 * @property string uuid    用户UUID
 * @property string email    邮箱
 * @property string phone    手机号
 * @property datetime email_verified_at    邮箱验证时间
 * @property string username    用户登录名
 * @property string nickname    昵称
 * @property string realname    实名
 * @property string password    密码
 * @property string avatar    头像
 * @property datetime last_login_at    最后登录日期
 * @property string last_ip    最后登录IP
 * @property int status    状态: 1=启用 0=禁用
 * @property datetime created_at
 * @property datetime updated_at
 * @property datetime deleted_at    删除时间 null未删除
 */
class SysUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'email',
        'phone',
        'email_verified_at',
        'username',
        'nickname',
        'realname',
        'password',
        'avatar',
        'last_login_at',
        'last_token',
        'last_ip',
        'status',
        'position',
        'department_id'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    protected $appends = ['department_name'];

    public function getDepartmentNameAttribute()
    {
        $name =  SysDepartment::where('id', $this->department_id)->value('department_name');
        return $name ?? "";
    }

    public function getDepartmentIdAttribute($value)
    {
        return $value ?? 0;
    }

    /**
     * User：caogang
     * DateTime：2021/5/17 11:18
     * @param $where
     * @param string[] $field
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function findUserByWhere($where, $field = ['*'])
    {
        return self::query()->where($where)->select($field)->first();
    }
}