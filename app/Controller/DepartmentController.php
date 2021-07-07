<?php
/**
 * 部门管理
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/17 14:31
 */

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Libs\Tree\Tree;
use App\Service\DepartmentService;
use Hyperf\Di\Annotation\Inject;

class DepartmentController extends BaseController
{

    /**
     * @Inject
     * @var DepartmentService
     */
    protected $departmentService;

    /**
     * 部门列表
     * User：caogang
     * DateTime：2021/5/17 14:32
     */
    public function list()
    {
        $list = $this->departmentService->getList();
        if ($list) {
            $list = Tree::makeTree($list);
        } else {
            $list = [];
        }
        return $this->success($list);
    }

    /**
     * 添加部门
     * User：caogang
     * DateTime：2021/5/17 14:32
     */
    public function create()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'department_name' => 'required',
            'parent_id'       => ['required', 'numeric'],
        ], [
            'department_name.required' => '部门名称不能为空',
            'parent_id.required'       => '请选择上级部门',
        ]);
        $ret = $this->departmentService->add($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * 修改部门
     * User：caogang
     * DateTime：2021/5/17 14:33
     */
    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'              => ['required', 'numeric'],
            'department_name' => 'required',
            'parent_id'       => ['required', 'numeric'],
        ], [
            'department_name.required' => '部门名称不能为空',
            'parent_id.required'       => '请选择上级部门',
        ]);
        $ret = $this->departmentService->update($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * 删除部门
     * User：caogang
     * DateTime：2021/5/17 14:33
     */
    public function delete()
    {
        $id = request()->input('id');
        if (!$id) {
            return $this->error(ErrorCode::PARAMS_LOSE);
        }
        $ret = $this->departmentService->destroy($id);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * 获取所有部门
     * User：caogang
     * DateTime：2021/5/17 15:09
     */
    public function select()
    {
        $list = $this->departmentService->getSelectList();
        if ($list) {
            $list = Tree::makeTree($list);
        } else {
            $list = [];
        }
        return $this->success($list);
    }
}