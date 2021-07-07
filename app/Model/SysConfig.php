<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/27 10:40
 */

namespace App\Model;


class SysConfig extends Model
{
    protected $table = 'sys_config';
    protected $fillable = [
        'key',
        'value',
        'remark',
        'group',
    ];
}