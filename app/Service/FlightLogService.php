<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/1 14:53
 */

namespace App\Service;


use App\Model\FlightLog;

class FlightLogService
{
    // 创建日志
    public static function createLog(string $operateMsg, $status = 1)
    {
        $arr = [
            'user_id'     => request()->userInfo['id'],
            'username'    => request()->userInfo['realname'],
            'route_path'  => request()->path(),
            'operate_msg' => $operateMsg,
            'status'      => $status
        ];
        return FlightLog::query()->create($arr);
    }
}