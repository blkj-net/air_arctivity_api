<?php
/**
 * 请求接口模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 14:08
 */

namespace App\Model;


class SysApi extends Model
{
    protected $table = 'sys_apis';
    protected $fillable = [
        'key',
        'name',
        'path',
        'description',
        'api_group',
        'method',
    ];
}