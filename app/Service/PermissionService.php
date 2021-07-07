<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/16 12:54
 */

namespace App\Service;

use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\SysDepartment;
use App\Model\SysDepartmentMenu;
use App\Model\SysMenu;
use App\Model\SysMenuApi;
use App\Model\SysRole;
use App\Model\SysRoleMenu;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class PermissionService
{
    /**
     * 获取权限列表
     * @Inject
     * @var SysMenu
     */
    protected $menuModel;

    /**
     * @Inject
     * @var RoleService
     */
    protected $roleService;

    /**
     * @Inject
     * @var DepartmentService
     */
    protected $departmentService;

    public function getRoleList($id)
    {
        $userRoleIds = request()->userInfo['role_ids'];
        $userRoleIds = $this->roleService->getAllChildIds($userRoleIds);
        $checkedRoleIds = $this->roleService->getAllChildIds($id);
        // 获取编辑的角色菜单权限
        $roleMenu = SysRoleMenu::whereIn('role_id', $checkedRoleIds)->select(['menu_id', DB::raw("GROUP_CONCAT(ability) as ability")])->groupBy('menu_id')->get()->toArray();
        $roleMenuList = array_combine(array_column($roleMenu, 'menu_id'), $roleMenu);
        if (request()->userInfo['id'] != Common::ADMIN_ID) { // 不是超级管理员
            // 用户的菜单权限
            $menu = SysRoleMenu::whereIn('role_id', $userRoleIds)->select(['menu_id', DB::raw("GROUP_CONCAT(ability) as ability")])->groupBy('menu_id')->get()->toArray();
            $menuList = array_combine(array_column($menu, 'menu_id'), $menu);
            $menuIds = array_column($menu, 'menu_id');
            $list = $this->menuModel->getMenuList($menuIds, ['title', 'id', 'parent_id']);
            foreach ($list as &$v) {
                $v->ability_list = [
                    [
                        "menuId"       => $v->id,
                        "apiId"        => 0,
                        "abilityKey"   => "list",
                        "abilityValue" => "查询",
                        "path"         => "",
                        "checkBox"     => true
                    ]
                ];
                $ability = SysMenuApi::where('menu_id', $v->id)->get()->toArray();
                $arr = [];
                foreach ($ability as &$vv) {
                    if ($v['menu_id'] == $v['menu_id'] && in_array($vv['ability_key'], explode(',', $menuList[$vv['menu_id']]['ability']))) {
                        if (in_array($vv['ability_key'], explode(',', $roleMenuList[$vv['menu_id']]['ability']))) {
                            $vv['check_box'] = true;
                        } else {
                            $vv['check_box'] = false;
                        }
                        $arr[] = $vv;
                    }
                }
                if ($arr) {
                    $v['ability_list'] = $arr;
                }
            }
        } else {
            $list = $this->menuModel->getMenuList([], ['title', 'id', 'parent_id']);
            foreach ($list as &$v) {
                $v->ability_list = [
                    [
                        "menuId"       => $v->id,
                        "apiId"        => 0,
                        "abilityKey"   => "list",
                        "abilityValue" => "查询",
                        "path"         => "",
                        "checkBox"     => true
                    ]
                ];
                $ability = SysMenuApi::where('menu_id', $v->id)->get()->toArray();
                foreach ($ability as &$vv) {
                    if (in_array($vv['ability_key'], explode(',', $roleMenuList[$vv['menu_id']]['ability']))) {
                        $vv['check_box'] = true;
                    } else {
                        $vv['check_box'] = false;
                    }
                }
                if ($ability) {
                    $v->ability_list = $ability;
                }
            }
        }
        return $list->toArray();
    }

    /**
     * 分配角色权限
     * User：caogang
     * DateTime：2021/5/18 16:26
     * @param array $params
     */
    public function roleAuth(array $params)
    {
        // 判断角色是否存在
        $role = SysRole::where('id', $params['role_id'])->first();
        if (!$role) {
            throw new BusinessException(ErrorCode::ROLE_NO_EXISTS);
        }
        if ($params['menus']) {
            DB::beginTransaction();
            try {
                // 删除角色的权限
                SysRoleMenu::query()->where('role_id', $params['role_id'])->delete();
                // 添加权限
                foreach ($params['menus'] as $v) {
                    $roleMenu = [
                        'role_id' => $params['role_id'],
                        'menu_id' => $v['menu_id'],
                        'ability' => $v['ability']
                    ];
                    SysRoleMenu::query()->updateOrCreate(['role_id' => $params['role_id'], 'menu_id' => $v['menu_id']], $roleMenu);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                p($e->getMessage(), '分配权限');
                return false;
            }
        }
        return true;
    }

    /**
     * 部门权限列表
     * User：caogang
     * DateTime：2021/5/20 9:50
     */
    public function getDepartmentList($id)
    {
        $userDepartmentIds = request()->userInfo['department_id'];
        $checkedDepartmentIds = $this->departmentService->getAllChildIds($id);
        // 获取传入的部门菜单权限
        $roleMenu = SysDepartmentMenu::whereIn('department_id', $checkedDepartmentIds)->select(['menu_id', DB::raw("GROUP_CONCAT(ability) as ability")])->groupBy('menu_id')->get()->toArray();
        $roleMenuList = array_combine(array_column($roleMenu, 'menu_id'), $roleMenu);
        if (request()->userInfo['id'] != Common::ADMIN_ID) { // 不是超级管理员
            $userDepartmentIds = $this->departmentService->getAllChildIds($userDepartmentIds);
            // 登录用户的菜单权限
            $menu = SysDepartmentMenu::whereIn('department_id', $userDepartmentIds)->select(['menu_id', DB::raw("GROUP_CONCAT(ability) as ability")])->groupBy('menu_id')->get()->toArray();
            $menuList = array_combine(array_column($menu, 'menu_id'), $menu);
            $menuIds = array_column($menu, 'menu_id');
            $list = $this->menuModel->getMenuList($menuIds, ['title', 'id', 'parent_id']);
            foreach ($list as &$v) {
                $v->ability_list = [
                    [
                        "menuId"       => $v->id,
                        "apiId"        => 0,
                        "abilityKey"   => "list",
                        "abilityValue" => "查询",
                        "path"         => "",
                        "checkBox"     => true
                    ]
                ];
                $ability = SysMenuApi::where('menu_id', $v->id)->get()->toArray();
                $arr = [];
                foreach ($ability as &$vv) {
                    if ($v['menu_id'] == $v['menu_id'] && in_array($vv['ability_key'], explode(',', $menuList[$vv['menu_id']]['ability']))) {
                        if (in_array($vv['ability_key'], explode(',', $roleMenuList[$vv['menu_id']]['ability']))) {
                            $vv['check_box'] = true;
                        } else {
                            $vv['check_box'] = false;
                        }
                        $arr[] = $vv;
                    }
                }
                if ($arr) {
                    $v['ability_list'] = $arr;
                }

            }
        } else {
            $list = $this->menuModel->getMenuList([], ['title', 'id', 'parent_id']);
            foreach ($list as &$v) {
                $v->ability_list = [
                    [
                        "menuId"       => $v->id,
                        "apiId"        => 0,
                        "abilityKey"   => "list",
                        "abilityValue" => "查询",
                        "path"         => "",
                        "checkBox"     => true
                    ]
                ];
                $ability = SysMenuApi::where('menu_id', $v->id)->get()->toArray();
                foreach ($ability as &$vv) {
                    if (in_array($vv['ability_key'], explode(',', $roleMenuList[$vv['menu_id']]['ability']))) {
                        $vv['check_box'] = true;
                    } else {
                        $vv['check_box'] = false;
                    }
                }
                if ($ability) {
                    $v->ability_list = $ability;
                }


            }
        }
        return $list->toArray();
    }

    /**
     * 分配部门权限
     * User：caogang
     * DateTime：2021/5/20 9:50
     */
    public function departmentAuth(array $params)
    {
        // 判断角色是否存在
        $role = SysDepartment::where('id', $params['department_id'])->first();
        if (!$role) {
            throw new BusinessException(ErrorCode::DEPARTMENT_NO_EXISTS);
        }
        if ($params['menus']) {
            DB::beginTransaction();
            try {
                // 删除角色的权限
                SysDepartmentMenu::query()->where('department_id', $params['department_id'])->delete();

//                $menuIds = array_column($params['menus'], 'menu_id');
//                $menuParentIds = SysMenu::whereIn('id', $menuIds)->pluck('all_parent_id')->toArray();
//                $menuParentIds = implode(',', $menuParentIds);
//                $menuParentIds = explode(',', $menuParentIds);
//                $menuParentIds = array_unique($menuParentIds);
//                foreach ($menuParentIds as $v) {
//                    $arr = [
//                        'role_id' => $params['role_id'],
//                        'menu_id' => $v,
//                    ];
//                    SysDepartmentMenu::query()->updateOrCreate($arr, $arr);
//                }
                // 添加权限
                foreach ($params['menus'] as $v) {
                    $arr = [
                        'department_id' => $params['department_id'],
                        'menu_id'       => $v['menu_id'],
                        'ability'       => $v['ability']
                    ];
                    SysDepartmentMenu::query()->updateOrCreate(['department_id' => $params['department_id'], 'menu_id' => $v['menu_id']], $arr);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                p($e->getMessage(), '分配部门权限');
                return false;
            }
        }
        return true;
    }

    /**
     * 角色API权限
     * User：caogang
     * DateTime：2021/5/19 14:59
     */
    public function roleAuthMenuList($roleIds)
    {
        $list = SysRoleMenu::whereIn('role_id', $roleIds)->get()->toArray();
        // 获取所有的菜单id
        $meunIds = array_column($list, 'menu_id');
        $list = array_combine(array_column($list, 'menu_id'), $list);
        $menuLists = SysMenuApi::whereIn('menu_id', $meunIds)->get()->toArray();
        $arrayList = [];
        foreach ($menuLists as &$v) {
            if (in_array($v['ability_key'], explode(',', $list[$v['menu_id']]['ability']))) {
                $arrayList[] = $v;
            }
        }
        $routes = array_column($arrayList, 'path');
        return $routes;
    }

    /**
     * 部门API权限
     */
    public function departmentAuthMenuList($departmentId)
    {
        $list = SysDepartmentMenu::where('department_id', $departmentId)->get()->toArray();
        // 获取所有的菜单id
        $meunIds = array_column($list, 'menu_id');
        $list = array_combine(array_column($list, 'menu_id'), $list);
        $menuLists = SysMenuApi::whereIn('menu_id', $meunIds)->get()->toArray();
        $arrayList = [];
        foreach ($menuLists as &$v) {
            if (in_array($v['ability_key'], explode(',', $list[$v['menu_id']]['ability']))) {
                $arrayList[] = $v;
            }
        }
        $routes = array_column($arrayList, 'path');
        return $routes;
    }
}