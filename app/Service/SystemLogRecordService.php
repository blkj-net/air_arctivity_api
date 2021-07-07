<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/27 9:54
 */

namespace App\Service;


use App\Model\SysOperationRecord;

class SystemLogRecordService extends BaseService
{
    /**
     * User：caogang
     * DateTime：2021/5/27 9:56
     * @param $page
     * @param $size
     * @param array $where
     * @param array $field
     * @return array
     */
    public function getList($page, $size, array $where, array $field, $realname, array $times): array
    {
        $list = SysOperationRecord::query()
            ->with('user:id,realname')
            ->whereHas('user', function ($query) use ($realname) {
                if ($realname) {
                    $query->where('realname', 'like', "%{$realname}%");
                }
            })
            ->where(function ($query) use ($where, $times) {
                $query->where($where);
                if ($times) {
                    $query->whereBetween('created_at', $times);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate($size, $field, 'page', $page)
            ->toArray();
        return $list;
    }

    /**
     * 创建日志
     * User：caogang
     * DateTime：2021/5/27 16:01
     * @param array $params
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model
     */
    public function create(array $params)
    {
        return SysOperationRecord::query()->create($params);
    }
}