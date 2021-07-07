<?php
/**
 * 权限控制器
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/16 12:18
 */

namespace App\Controller;


use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Libs\Tree\Tree;
use App\Model\SysMenuApi;
use App\Model\SysRoleMenu;
use App\Service\PermissionService;
use Hyperf\Di\Annotation\Inject;

class PermissionController extends BaseController
{
    /**
     * @Inject
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * 角色权限列表
     * User：caogang
     * DateTime：2021/5/18 16:19
     * @return array
     */
    public function roleList()
    {
        $id = request()->input('id');
        if (!$id) {
            return $this->error(ErrorCode::PARAMS_LOSE);
        }
        $list = $this->permissionService->getRoleList($id);
        $arrayList = [];
        foreach ($list as $k => $v) {
            $arrayList[$k]['id'] = $v['id'];
            $arrayList[$k]['key'] = $v['id'];
            $arrayList[$k]['parent_id'] = $v['parent_id'];
            $arrayList[$k]['value'] = $v['id'];
            $arrayList[$k]['title'] = $v['title'];
            $arrayList[$k]['checkable'] = true;
            $arrayList[$k]['disable_checkbox'] = false;
            $arrayList[$k]['disabled'] = false;
            $arrayList[$k]['selectable'] = true;
            $arrayList[$k]['is_leaf'] = false;
            $arrayList[$k]['extend'] = $v;
            $arrayList[$k]['slots'] = ['title' => 'custom'];
        }
        if ($arrayList) {
            $list = Tree::makeTree($arrayList);
        } else {
            $list = [];
        }
        return $this->success($list);
    }

    /**
     * 分配角色权限
     * User：caogang
     * DateTime：2021/5/18 16:20
     */
    public function roleAuth()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'role_id' => ['required', 'numeric'],
            'menus'   => 'required'
        ]);
        $ret = $this->permissionService->roleAuth($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::OPERATE_ERROR);
        }
    }

    /**
     * 部门权限列表
     * User：caogang
     * DateTime：2021/5/20 9:48
     */
    public function departmentList()
    {
        $id = request()->input('id');
        if (!$id) {
            return $this->error(ErrorCode::PARAMS_LOSE);
        }
        $list = $this->permissionService->getDepartmentList($id);

        $arrayList = [];
        foreach ($list as $k => $v) {
            $arrayList[$k]['id'] = $v['id'];
            $arrayList[$k]['key'] = $v['id'];
            $arrayList[$k]['parent_id'] = $v['parent_id'];
            $arrayList[$k]['value'] = $v['id'];
            $arrayList[$k]['title'] = $v['title'];
            $arrayList[$k]['checkable'] = true;
            $arrayList[$k]['disable_checkbox'] = false;
            $arrayList[$k]['disabled'] = false;
            $arrayList[$k]['selectable'] = true;
            $arrayList[$k]['is_leaf'] = false;
            $arrayList[$k]['extend'] = $v;
            $arrayList[$k]['slots'] = ['title' => 'custom'];
        }
        if ($arrayList) {
            $list = Tree::makeTree($arrayList);
        } else {
            $list = [];
        }
        return $this->success($list);
    }

    /**
     * 分配部门权限
     * User：caogang
     * DateTime：2021/5/20 9:48
     */
    public function departmentAuth()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'department_id' => ['required', 'numeric'],
            'menus'         => 'required'
        ]);
        $ret = $this->permissionService->departmentAuth($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::PARAMS_LOSE);
        }
    }

}