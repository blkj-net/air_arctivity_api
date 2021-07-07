<?php
/**
 * 菜单服务
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 14:01
 */

namespace App\Service;

use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Libs\Tree\Tree;
use App\Model\SysApi;
use App\Model\SysDepartmentMenu;
use App\Model\SysMenu;
use App\Model\SysMenuApi;
use App\Model\SysRoleMenu;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class MenuService extends BaseService
{
    /**
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

    /**
     * 获取所有菜单
     * User：caogang
     * DateTime：2021/5/14 15:25
     */
    public function getMenuList(): array
    {
        $list = $this->menuModel->getMenuList();
        $arr = [];
        foreach ($list as $k => $v) {
            $ability = SysMenuApi::query()->where('menu_id', $v->id)->pluck('api_id')->toArray();
            $arr[$k]['id'] = $v->id;
            $arr[$k]['key'] = $v->id;
            $arr[$k]['parent_id'] = $v->parent_id;
            $arr[$k]['value'] = $v->id;
            $arr[$k]['title'] = $v->title;
            $arr[$k]['checkable'] = true;
            $arr[$k]['disable_checkbox'] = false;
            $arr[$k]['disabled'] = false;
            $arr[$k]['selectable'] = true;
            $arr[$k]['is_leaf'] = false;
            $arr[$k]['extend'] = $v->toArray();
            $arr[$k]['extend']['ability'] = $ability;
        }
        return $arr ? Tree::makeTree($arr) : [];
    }

    /**
     * 创建
     * User：caogang
     * DateTime：2021/5/13 16:55
     * @param array $params
     * @return SysMenu|bool|\Hyperf\Database\Model\Model
     */
    public function addMenu(array $params)
    {
        // name值唯一
        $nameExists = $this->menuModel->where('name', $params['name'])->exists();
        if ($nameExists) {
            throw new BusinessException(1000, 'name值已存在');
        }
        DB::beginTransaction();
        try {
            if ($params['parent_id'] != Common::MENU_PARENT_ID) {
                $allparentId = $this->menuModel->where('id', $params['parent_id'])->value('all_parent_id');
                $str = $allparentId . ',' . $params['parent_id'];
                $params['all_parent_id'] = trim($str, ',');
            }
            // 添加菜单
            $menu_id = $this->menuModel->create($params)->id;

            // 菜单绑定api接口
            if (isset($params['ability']) && $params['ability']) { // 绑定接口
                $apiIds = explode(',', $params['ability']);
                $apiList = SysApi::query()->whereIn('id', $apiIds)->get();
                foreach ($apiList as $v) {
                    $apiMenuData = [
                        'menu_id'       => $menu_id,
                        'api_id'        => $v->id,
                        'ability_key'   => $v->key,
                        'ability_value' => $v->name,
                        'path'          => $v->path,
                    ];
                    SysMenuApi::insert($apiMenuData);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            p($e->getMessage(), '创建菜单失败');
            return false;
        }
        return true;
    }

    /**
     * 更新菜单
     * User：caogang
     * DateTime：2021/5/14 15:19
     * @param array $params
     * @return bool
     */
    public function editMenu(array $params)
    {
        DB::beginTransaction();
        try {
            // 判断子节点中，是否存在该节点
            $child = $this->menuModel->whereRaw('FIND_IN_SET(?,all_parent_id)', $params['id'])->where('id', $params['parent_id'])->first();
            if ($child) {
                throw new BusinessException(ErrorCode::MENU_NODE_FAIL);
            }

            if ($params['parent_id'] != Common::MENU_PARENT_ID) {
                $allparentId = $this->menuModel->where('id', $params['parent_id'])->value('all_parent_id');
                $str = $allparentId . ',' . $params['parent_id'];
                $params['all_parent_id'] = trim($str, ',');
            }
            // 更新菜单
            $this->menuModel->where('id', $params['id'])->update(SysMenu::fillableFromArray($params));
            // 删除绑定api
            SysMenuApi::where('menu_id', $params['id'])->delete();
            // 菜单绑定api接口
            if (isset($params['ability']) && $params['ability']) { // 绑定接口
                $apiIds = explode(',', $params['ability']);
                $apiList = SysApi::query()->whereIn('id', $apiIds)->get();
                foreach ($apiList as $v) {
                    $apiMenuData = [
                        'menu_id'       => $params['id'],
                        'api_id'        => $v->id,
                        'ability_key'   => $v->key,
                        'ability_value' => $v->name,
                        'path'          => $v->path,
                    ];
                    SysMenuApi::insert($apiMenuData);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            p($e->getMessage(), '更新菜单失败');
            return false;
        }
        return true;
    }

    public function delete($id)
    {
        // 判断该菜单是否有子级菜单
        if ($this->menuModel->where('parent_id', $id)->exists()) {
            throw new BusinessException(ErrorCode::MENU_EXISTS_CHILD);
        }
        DB::beginTransaction();
        try {
            SysMenuApi::where('menu_id', $id)->delete();
            $this->menuModel->destroy($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            p($e->getMessage(), '删除菜单失败');
            return false;
        }
        return true;
    }

    /**
     * 用户菜单权限
     * User：caogang
     * DateTime：2021/6/11 10:54
     */
    public function userMenu()
    {
        $roleIds = request()->userInfo['role_ids'];
        $departmentId = request()->userInfo['department_id'];
        $roleIds = $this->roleService->getAllChildIds($roleIds);
        $departmentIds = $this->departmentService->getAllChildIds($departmentId);
        $roleMenu = SysRoleMenu::whereIn('role_id', $roleIds)->select(['menu_id', DB::raw("GROUP_CONCAT(ability) as ability")])->groupBy('menu_id')->get()->toArray();
        $departmentMenu = SysDepartmentMenu::whereIn('department_id', $departmentIds)->select(['menu_id', DB::raw("GROUP_CONCAT(ability) as ability")])->groupBy('menu_id')->get()->toArray();
        $menu = array_merge($roleMenu, $departmentMenu);
        $menuList = [];
        foreach ($menu as $item) {
            $arr = [
                'menu_id' => $item['menu_id'],
                'ability' => $item['ability']
            ];
            if (isset($menuList[$item['menu_id']])) {
                $arr['ability'] = implode(",", array_unique(array_merge(explode(",", $arr['ability']), explode(",", $menuList[$item['menu_id']]['ability']))));
            }
            $menuList[$item['menu_id']] = $arr;
        }
        return $menuList;
    }

    /**
     * 渲染菜单列表
     * User：caogang
     * DateTime：2021/5/14 15:24
     */
    public function renderMenuList()
    {
        $authMenu = $this->userMenu();
        $menuIds = array_column($authMenu, 'menu_id');
        $roleMenu = array_combine(array_column($authMenu, 'menu_id'), $authMenu);
        if (request()->userInfo['id'] == Common::ADMIN_ID) {
            $menuIds = [];
            $menuList = $this->menuModel->getMenuList($menuIds)->toArray();
        } else {
            if ($menuIds) {
                $menuList = $this->menuModel->getMenuList($menuIds)->toArray();
            } else {
                $menuList = [];
            }
        }
        foreach ($menuList as $k => &$v) {
            if (request()->userInfo['id'] == Common::ADMIN_ID) {
                $ability = SysMenuApi::where('menu_id', $v['id'])->groupBy('ability_key')->pluck('ability_key')->toArray();
                if ($ability) {
                    $v['ability'] = $ability;
                } else {
                    $v['ability'] = [];
                    $v['ability'] = [];
                }
            } else {
                if (isset($roleMenu[$v['id']]['ability']) && trim($roleMenu[$v['id']]['ability'], ',')) {
                    $v['ability'] = explode(',', $roleMenu[$v['id']]['ability']);
                } else {
                    $v['ability'] = [];
                }
            }
        }
        return $menuList;
    }

}