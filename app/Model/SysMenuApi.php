<?php
/**
 * 菜单、接口中间表模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 14:08
 */

namespace App\Model;


class SysMenuApi extends Model
{
    protected $table = 'sys_menu_apis';
    protected $fillable = [
        'menu_id',
        'api_id',
        'ability_key',
        'ability_value',
        'path'
    ];
    public $timestamps = false;
}