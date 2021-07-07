<?php
/**
 * 菜单管理
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 13:42
 */

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Libs\Tree\Tree;
use App\Service\MenuService;
use Hyperf\Di\Annotation\Inject;

class MenuController extends BaseController
{

    /**
     * @Inject
     * @var MenuService
     */
    protected $menuService;

    /**
     * 菜单列表
     * User：caogang
     * DateTime：2021/5/13 13:44
     */
    public function getList()
    {
        $list = $this->menuService->getMenuList();
        return $this->success($list);
    }

    /**
     * 创建菜单
     * User：caogang
     * DateTime：2021/5/13 13:44
     */
    public function create()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'title'     => 'required',
            'parent_id' => ['required', 'numeric'],
            'path'      => 'required'
        ], [
            'title.required'     => '菜单名称不能为空',
            'parent_id.required' => '请选择上级菜单',
            'parent_id.numeric'  => '上级菜单格式合法',
            'path.required'      => '路由路径不能为空',
        ]);
        $ret = $this->menuService->addMenu($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::CREATE_ERROR);
        }
    }

    /**
     * 修改菜单
     * User：caogang
     * DateTime：2021/5/13 13:44
     */
    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'        => ['required', 'numeric'],
            'title'     => 'required',
            'parent_id' => ['required', 'numeric'],
            'path'      => 'required',
        ], [
            'title.required'     => '菜单名称不能为空',
            'parent_id.required' => '请选择上级菜单',
            'parent_id.numeric'  => '上级菜单格式合法',
            'path.required'      => '路由路径不能为空',
        ]);
        $ret = $this->menuService->editMenu($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 删除菜单
     * User：caogang
     * DateTime：2021/5/13 13:45
     */
    public function delete()
    {
        $id = request()->input('id');
        if (!$id) {
            return $this->error(ErrorCode::PARAMS_LOSE);
        }
        $ret = $this->menuService->delete($id);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    /**
     * 获取渲染菜单列表
     * User：caogang
     * DateTime：2021/5/19 15:59
     */
    public function renderMenuList()
    {
        $list = $this->menuService->renderMenuList();
        $arrayList = [];
        foreach ($list as $k => $v) {
            $arrayList[$k]['id'] = $v['id'];
            $arrayList[$k]['parent_id'] = $v['parent_id'];
            $arrayList[$k]['name'] = $v['name'];
            $arrayList[$k]['path'] = $v['path'];
            $arrayList[$k]['component'] = $v['component'];
            $arrayList[$k]['redirect'] = $v['redirect'];
            $arrayList[$k]['meta'] = [
                "hidden"      => $v['hidden'] ? true : false,
                "title"       => $v['title'],
                "description" => $v['description'],
                "icon"        => $v['icon'],
                "always_show" => $v['always_show'] ? true : false,
                "ability"     => $v['ability']
            ];
        }
        if ($arrayList) {
            $arrayList = Tree::makeTree($arrayList);
        } else {
            $arrayList = [];
        }
        return $this->success($arrayList);
    }
}

