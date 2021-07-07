<?php
/**
 * 菜单模型
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 14:08
 */

namespace App\Model;


class SysMenu extends Model
{
    protected $table = 'sys_menus';
    protected $fillable = [
        'menu_level',
        'parent_id',
        'path',
        'name',
        'hidden',
        'component',
        'sort',
        'keep_alive',
        'default_menu',
        'title',
        'description',
        'icon',
        'close_tab',
        'hidden_tab',
        'role_mode',
        'all_parent_id',
        'redirect',
        'always_show'
    ];

    protected $hidden = [
        'keep_alive'
    ];
    /**
     * 菜单列表
     * User：caogang
     * DateTime：2021/5/14 9:19
     */
    public function getMenuList(array $menuIds = [], $field = ['*'])
    {
        $query = $this->select($field)->orderBy('sort', 'asc');
        if ($menuIds){
            $query->whereIn('id', $menuIds);
        }
        return $query->get();
    }

}