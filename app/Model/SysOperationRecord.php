<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/27 9:17
 */

namespace App\Model;


class SysOperationRecord extends Model
{
    protected $table = 'sys_operation_records';
    protected $fillable = [
        'ip',
        'method',
        'path',
        'status',
        'latency',
        'agent',
        'error_message',
        'request_body',
        'response_body',
        'user_id',
        'level'
    ];

    public function user()
    {
        return $this->belongsTo(SysUser::class, 'user_id', 'id');
    }
}