<?php
/**
 * 航班配置
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 17:01
 */

namespace App\Controller;


use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Service\FlightConfigService;
use App\Service\FlightLogService;
use Hyperf\Di\Annotation\Inject;

class FlightConfigController extends BaseController
{
    /**
     * @Inject
     * @var FlightConfigService
     */
    protected $flightConfigService;

    /**
     * @Inject
     * @var FlightLogService
     */
    protected $logService;

    /**
     * 列表
     * User：caogang
     * DateTime：2021/5/28 17:23
     * @return array
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $where = [];
        $field = ['*'];
        $list = $this->flightConfigService->getList($page, $size, $where, $field);
        foreach ($list['data'] as &$v) {
            $v['schedule'] = array_column($v['schedule_list'], 'schedule');
            $v['schedule_str'] = implode('/', $v['schedule']);
        }
        return $this->success($list);
    }

    /**
     * 添加
     * User：caogang
     * DateTime：2021/5/28 17:23
     * @return array
     */
    public function create()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'flight_info_id'                  => ['required', 'numeric', 'exists:flight_info,id'],
            'sale_start_time'                 => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:' . date('Y-m-d H:i:s')],
            'sale_end_time'                   => ['required', 'date_format:Y-m-d H:i:s', 'after:sale_start_time'],
            'flight_hours_ago'                => ['required', 'numeric', 'gte:0'],
            'schedule_list'                   => ['required', 'array'],
            'schedule_list.*.schedule'        => ['required', 'numeric', 'in:1,2,3,4,5,6,7'],
            'schedule_list.*.original_price'  => ['required', 'numeric', 'gte:0'],
            'schedule_list.*.discount_amount' => ['required', 'numeric', 'lte:schedule_list.*.original_price']
        ], [
            'flight_info_id.required'             => '请选择航班信息',
            'flight_info_id.numeric'              => '航班信息参数无效',
            'flight_info_id.exists'               => '选择的航班信息不存在',
            'sale_start_time.required'            => '销售起始日期不能为空',
            'sale_start_time.date_format'         => '销售起始日期格式为：2021-05-28 17:00:00',
            'sale_start_time.after_or_equal'      => "销售起始日期不能大于当前时间",
            'sale_end_time.required'              => '销售截止日期不能为空',
            'sale_end_time.date_format'           => '销售截止日期格式为：2021-05-28 17:00:00',
            'sale_end_time.after'                 => '销售截止日期必须大于销售起始日期',
            'flight_hours_ago.required'           => '停止售卖时间不能为空',
            'flight_hours_ago.numeric'            => '停止售卖时间必须为数字',
            'flight_hours_ago.gte'                => '停止售卖时间必须大于0',
            'schedule_list.*.schedule.in'         => '班期列表星期参数无效',
            'schedule_list.*.original_price.gte'  => '原价必须大于0',
            'schedule_list.*.discount_amount.lte' => '优惠价必须小于等于原价',
        ]);
        $ret = $this->flightConfigService->create($params);
        $logMsg = "添加航班配置";
        if ($ret) {
            $this->logService->createLog($logMsg, $params);
            return $this->success();
        } else {
            $this->logService->createLog($logMsg, 0);
            return $this->error(ErrorCode::CREATE_ERROR);
        }
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/5/28 17:22
     * @return array
     */
    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'                              => ['required', 'numeric', 'exists:flight_config,id'],
            'flight_info_id'                  => ['required', 'numeric', 'exists:flight_info,id'],
            'sale_start_time'                 => ['required', 'date_format:Y-m-d H:i:s'],
            'sale_end_time'                   => ['required', 'date_format:Y-m-d H:i:s', 'after:sale_start_time'],
            'flight_hours_ago'                => ['required', 'numeric', 'gte:0'],
            'schedule_list'                   => ['required', 'array'],
            'schedule_list.*.schedule'        => ['required', 'numeric', 'in:1,2,3,4,5,6,7'],
            'schedule_list.*.original_price'  => ['required', 'numeric', 'gte:0'],
            'schedule_list.*.discount_amount' => ['required', 'numeric', 'lte:schedule_list.*.original_price']
        ], [
            'id.required'                         => 'id参数不能为空',
            'id.numeric'                          => 'id参数无效',
            'id.exists'                           => '该条数据不存在',
            'flight_info_id.required'             => '请选择航班信息',
            'flight_info_id.numeric'              => '航班信息参数无效',
            'flight_info_id.exists'               => '选择的航班信息不存在',
            'sale_start_time.required'            => '销售起始日期不能为空',
            'sale_start_time.date_format'         => '销售起始日期格式为：2021-05-28 17:00:00',
            'sale_end_time.required'              => '销售截止日期不能为空',
            'sale_end_time.date_format'           => '销售截止日期格式为：2021-05-28 17:00:00',
            'sale_end_time.after'                 => '销售截止日期必须大于销售起始日期',
            'flight_hours_ago.required'           => '停止售卖时间不能为空',
            'flight_hours_ago.numeric'            => '停止售卖时间必须为数字',
            'flight_hours_ago.gte'                => '停止售卖时间必须大于等于0',
            'schedule_list.*.schedule.in'         => '班期列表星期参数无效',
            'schedule_list.*.original_price.gte'  => '原价必须大于0',
            'schedule_list.*.discount_amount.lte' => '优惠价必须小于等于原价',
        ]);
        $ret = $this->flightConfigService->update($params['id'], $params);
        $logMsg = "更新航班配置";
        if ($ret) {
            $this->logService->createLog($logMsg, $params);
            return $this->success();
        } else {
            $this->logService->createLog($logMsg, 0);
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 单个删除
     * User：caogang
     * DateTime：2021/5/28 17:22
     * @return array
     */
    public function delete()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id' => ['required', 'numeric', 'exists:flight_config,id'],
        ], [
            'id.required' => 'id参数不能为空',
            'id.numeric'  => 'id参数无效',
            'id.exists'   => '该条记录不存在',
        ]);
        $ret = $this->flightConfigService->destroy($params['id']);
        $logMsg = "删除航班配置";
        if ($ret) {
            $this->logService->createLog($logMsg);
            return $this->success();
        } else {
            $this->logService->createLog($logMsg, 0);
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    /**
     * 批量删除
     * User：caogang
     * DateTime：2021/5/28 17:22
     * @return array
     */
    public function batchDelete()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'ids' => ['required', 'array'],
        ], [
            'id.required' => 'id参数不能为空',
            'id.array'    => 'id参数必须为数组',
        ]);
        $ret = $this->flightConfigService->destroy($params['ids']);
        $logMsg = "批量删除航班配置";
        if ($ret) {
            $this->logService->createLog($logMsg);
            return $this->success();
        } else {
            $this->logService->createLog($logMsg, 0);
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    /**
     * 创建库存
     * User：caogang
     * DateTime：2021/5/30 17:23
     */
    public function createStock()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => '缺少参数id',
            'id.numeric'  => '参数id无效'
        ]);
        $ret = $this->flightConfigService->createStock($params['id']);
        $logMsg = "通过航班配置生成库存";
        if ($ret) {
            $this->logService->createLog($logMsg);
            return $this->success();
        } else {
            $this->logService->createLog($logMsg, 0);
            return $this->error();
        }
    }
}