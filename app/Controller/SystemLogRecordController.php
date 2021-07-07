<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/27 9:53
 */

namespace App\Controller;

use App\Constants\Common;
use App\Service\SystemLogRecordService;
use Hyperf\Di\Annotation\Inject;

class SystemLogRecordController extends BaseController
{
    /**
     * @Inject
     * @var SystemLogRecordService
     */
    protected $systemLogRecordService;

    /**
     * 获取列表
     * User：caogang
     * DateTime：2021/5/27 9:59
     * @return array
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $method = request()->input('method');
        $level = request()->input('level');
        $times = request()->input('times', []);
        $realname = request()->input('realname');
        $where = [];
        if ($method) {
            $where[] = ['method', '=', $method];
        }
        if ($level) {
            $where[] = ['level', '=', $level];
        }
        $field = ['*'];
        $list = $this->systemLogRecordService->getList($page, $size, $where, $field, $realname, $times);
        foreach ($list['data'] as &$v) {
            $v['realname'] = $v['user']['realname'];
            unset($v['user']);
        }
        return $this->success($list);
    }

}