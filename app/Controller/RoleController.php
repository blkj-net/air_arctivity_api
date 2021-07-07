<?php
/**
 * 角色管理
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/14 16:15
 */

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Libs\Tree\Tree;
use App\Service\RoleService;
use Hyperf\Di\Annotation\Inject;

class RoleController extends BaseController
{
    /**
     * @Inject
     * @var RoleService
     */
    protected $roleService;

    /**
     * 获取列表
     * User：caogang
     * DateTime：2021/5/17 13:57
     * @return array
     */
    public function list()
    {

        $list = $this->roleService->getList();
        if ($list) {
            $list = Tree::makeTree($list);
        } else {
            $list = [];
        }
        return $this->success($list);
    }

    /**
     * 添加
     * User：caogang
     * DateTime：2021/5/14 16:51
     * @return array
     */
    public function create()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'role_name' => 'required',
            'parent_id' => ['required', 'numeric']
        ]);
        $ret = $this->roleService->create($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::CREATE_ERROR);
        }
    }

    /**
     * 修改
     * User：caogang
     * DateTime：2021/5/14 16:51
     * @return array
     */
    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'        => ['required', 'numeric'],
            'role_name' => 'required',
            'parent_id' => ['required', 'numeric']
        ]);
        $ret = $this->roleService->update($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 删除
     * User：caogang
     * DateTime：2021/5/14 16:51
     * @return array
     */
    public function delete()
    {
        $id = request()->input('id');
        if (!$id) {
            throw new BusinessException(ErrorCode::PARAMS_LOSE);
        }
        $ret = $this->roleService->delete($id);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    /**
     * 批量删除
     * User：caogang
     * DateTime：2021/5/19 13:55
     * @return array
     */
    public function batchDelete()
    {
        $ids = request()->input('ids');
        if (!$ids) {
            throw new BusinessException(ErrorCode::PARAMS_LOSE);
        }
        $ret = $this->roleService->delete($ids);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    /**
     * 角色下拉列表
     * User：caogang
     * DateTime：2021/5/19 15:15
     */
    public function select()
    {
        $list = $this->roleService->selectList();
        if ($list) {
            $list = Tree::makeTree($list);
        } else {
            $list = [];
        }
        return $this->success($list);
    }
}