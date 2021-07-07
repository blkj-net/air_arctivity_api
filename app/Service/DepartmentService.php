<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/17 14:41
 */

namespace App\Service;


use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\SysDepartment;

class DepartmentService extends BaseService
{
    /**
     * 获取部门列表
     * User：caogang
     * DateTime：2021/5/17 14:49
     * @return array
     */
    public function getList(): array
    {
        $field = ['id', 'department_name', 'parent_id', 'remark'];
        // 首先获取顶级角色
        $list = SysDepartment::query()->select($field)->get()->toArray();
        return $list;
    }

    /**
     * 添加部门
     * User：caogang
     * DateTime：2021/5/17 14:49
     */
    public function add(array $params)
    {
        if (SysDepartment::query()->where('department_name', $params['department_name'])->exists()) {
            throw new BusinessException(ErrorCode::NAME_ALREADY_EXISTS);
        }
        if ($params['parent_id'] != 0) {
            $allparentId = SysDepartment::where('id', $params['parent_id'])->value('all_parent_id');
            $str = $allparentId . ',' . $params['parent_id'];
            $params['all_parent_id'] = trim($str, ',');
        }
        return SysDepartment::query()->create($params);
    }

    /**
     * 修改
     * User：caogang
     * DateTime：2021/5/17 14:56
     * @param array $params
     * @return int
     */
    public function update(array $params)
    {
        $where = [
            ['id', '!=', $params['id']],
            ['department_name', '=', $params['department_name']]
        ];
        if (SysDepartment::query()->where($where)->exists()) {
            throw new BusinessException(ErrorCode::NAME_ALREADY_EXISTS);
        }
        // 判断子节点中，是否存在该节点
        $child = SysDepartment::whereRaw('FIND_IN_SET(?,all_parent_id)', $params['id'])->where('id', $params['parent_id'])->first();
        if ($child) {
            throw new BusinessException(ErrorCode::DEPARTMENT_NODE_FAIL);
        }
        if ($params['parent_id'] != Common::DEPARTMENT_PARENT_ID) {
            $allparentId = SysDepartment::where('id', $params['parent_id'])->value('all_parent_id');
            $str = $allparentId . ',' . $params['parent_id'];
            $params['all_parent_id'] = trim($str, ',');
        }
        return SysDepartment::query()->where('id', $params['id'])->update(SysDepartment::fillableFromArray($params));
    }

    /**
     * 删除
     * User：caogang
     * DateTime：2021/5/17 15:10
     * @param mixed $id array 批量删除 int 单个删除
     * @return int
     */
    public function destroy($id): int
    {
        return SysDepartment::destroy($id);
    }

    /**
     * 获取所有的部门
     * User：caogang
     * DateTime：2021/5/17 15:12
     */
    public function getSelectList(): array
    {
        $data = SysDepartment::query()->select(['id', 'department_name', 'parent_id'])->get();
        $arrayList = [];
        foreach ($data as $k => $v) {
            $arrayList[$k]['id'] = $v->id;
            $arrayList[$k]['key'] = $v->id;
            $arrayList[$k]['parent_id'] = $v->parent_id;
            $arrayList[$k]['value'] = $v->id;
            $arrayList[$k]['title'] = $v->department_name;
            $arrayList[$k]['checkable'] = true;
            $arrayList[$k]['disable_checkbox'] = false;
            $arrayList[$k]['disabled'] = false;
            $arrayList[$k]['selectable'] = true;
            $arrayList[$k]['is_leaf'] = false;
            $arrayList[$k]['extend'] = $v->toArray();
        }
        return $arrayList;
    }

    /**
     * 获取所有子节点id（包含父级节点id）
     * User：caogang
     * DateTime：2021/5/20 11:21
     */
    public function getAllChildIds($id): array
    {
        $ids = [];
        if (is_array($id)) {
            $ids = array_merge($ids, $id);
            foreach ($id as $v) {
                $arr = SysDepartment::whereRaw('FIND_IN_SET(?,all_parent_id)', $v)->pluck('id')->toArray();
                $ids = array_merge($ids, $arr);
            }
        } else {
            $ids[] = $id;
            $arr = SysDepartment::whereRaw('FIND_IN_SET(?,all_parent_id)', $id)->pluck('id')->toArray();
            $ids = array_merge($ids, $arr);
        }
        return $ids;
    }

}