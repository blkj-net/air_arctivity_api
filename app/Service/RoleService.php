<?php
/**
 * 角色Services
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/14 16:18
 */

namespace App\Service;

use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\SysRole;
use Hyperf\Di\Annotation\Inject;

class RoleService extends BaseService
{

    /**
     * @Inject
     * @var SysRole
     */
    protected $roleModel;

    /**
     * 获取列表
     * User：caogang
     * DateTime：2021/5/24 13:53
     * @return array
     */
    public function getList(): array
    {
        $where = [];
        $field = ['id', 'role_name', 'parent_id', 'remark'];
        $list = $this->roleModel->select($field)->where($where)->get()->toArray();
        return $list;
    }

    /**
     * User：caogang
     * DateTime：2021/5/24 13:53
     * @param $params
     * @return SysRole|\Hyperf\Database\Model\Model
     */
    public function create($params): SysRole
    {
        // 判断该角色是否存在
        if ($this->roleModel->where('role_name', $params['role_name'])->exists()) {
            throw new BusinessException(ErrorCode::NAME_ALREADY_EXISTS);
        }
        if ($params['parent_id'] != 0) {
            $allparentId = $this->roleModel->where('id', $params['parent_id'])->value('all_parent_id');
            $str = $allparentId . ',' . $params['parent_id'];
            $params['all_parent_id'] = trim($str, ',');
        }
        return $this->roleModel->create($params);
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/5/24 13:56
     * @param $params
     * @return bool|int
     */
    public function update($params): int
    {
        $where = [
            ['id', '!=', $params['id']],
            ['role_name', '=', $params['role_name']]
        ];
        if ($this->roleModel->where($where)->exists()) {
            throw new BusinessException(ErrorCode::NAME_ALREADY_EXISTS);
        }
        // 判断子节点中，是否存在该节点
        $child = $this->roleModel->whereRaw('FIND_IN_SET(?,all_parent_id)', $params['id'])->where('id', $params['parent_id'])->first();
        if ($child) {
            throw new BusinessException(ErrorCode::ROLE_NODE_FAIL);
        }
        if ($params['parent_id'] != Common::ROLE_PARENT_ID) { // 判断是否为顶级
            $allparentId = $this->roleModel->where('id', $params['parent_id'])->value('all_parent_id');
            $str = $allparentId . ',' . $params['parent_id'];
            $params['all_parent_id'] = trim($str, ',');
        }
        return $this->roleModel->where('id', $params['id'])->update(SysRole::fillableFromArray($params));
    }

    /**
     * 删除
     * User：caogang
     * DateTime：2021/5/24 13:58
     * @param $id
     * @return int
     */
    public function delete($id): int
    {
        return $this->roleModel->destroy($id);
    }

    /**
     * 获取所有的角色列表
     * User：caogang
     * DateTime：2021/5/19 15:20
     * @return array
     */
    public function selectList(): array
    {
        $list = SysRole::select(['id', 'role_name', 'parent_id', 'default_router'])->get()->toArray();
        $arr = [];
        foreach ($list as $k => &$v) {
            $arr[$k]['id'] = $v['id'];
            $arr[$k]['key'] = $v['id'];
            $arr[$k]['value'] = $v['id'];
            $arr[$k]['parent_id'] = $v['parent_id'];
            $arr[$k]['title'] = $v['role_name'];
            $arr[$k]['checkable'] = true;
            $arr[$k]['disable_checkbox'] = false;
            $arr[$k]['disabled'] = false;
            $arr[$k]['selectable'] = true;
            $arr[$k]['is_leaf'] = false;
            $arr[$k]['extend'] = $v;
        }
        return $arr;
    }

    /**
     * 获取所有子节点id（包含父级节点id）
     * User：caogang
     * DateTime：2021/5/20 11:21
     */
    public function getAllChildIds($roleId): array
    {
        $ids = [];
        if (is_array($roleId)) {
            $ids = array_merge($ids, $roleId);
            foreach ($roleId as $v) {
                $arr = SysRole::whereRaw('FIND_IN_SET(?,all_parent_id)', $v)->pluck('id')->toArray();
                $ids = array_merge($ids, $arr);
            }
        } else {
            $ids[] = $roleId;
            $arr = SysRole::whereRaw('FIND_IN_SET(?,all_parent_id)', $roleId)->pluck('id')->toArray();
            $ids = array_merge($ids, $arr);
        }
        return $ids;
    }

}