<?php
/**
 * 航班信息管理
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 15:36
 */

namespace App\Controller;


use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Service\FlightInfoService;
use App\Service\FlightLogService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Rule;

class FlightInfoController extends BaseController
{
    /**
     * @Inject
     * @var FlightInfoService
     */
    protected $flightInfoService;

    /**
     * @Inject
     * @var FlightLogService
     */
    protected $logService;

    /**
     * 列表
     * User：caogang
     * DateTime：2021/5/28 15:37
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $flightName = request()->input('flight_name');
        $startAirportsCode = request()->input('start_airports_code');
        $endAirportsCode = request()->input('end_airports_code');
        $where = [];
        // 航班号
        if ($flightName) {
            $where[] = ['flight_name', '=', $flightName];
        }
        // 起飞机场三字码
        if ($startAirportsCode) {
            $where[] = ['start_airports_code', '=', $startAirportsCode];
        }
        // 到达机场三字码
        if ($endAirportsCode) {
            $where[] = ['end_airports_code', '=', $endAirportsCode];
        }
        $field = ['*'];
        $list = $this->flightInfoService->getList($page, $size, $where, $field);
        return $this->success($list);
    }

    /**
     * 获取所有数据
     * User：caogang
     * DateTime：2021/5/28 16:20
     * @return array
     */
    public function select()
    {
        $list = $this->flightInfoService->selectList();
        return $this->success($list);
    }

    public function info()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id' => ['required', 'numeric', 'exists:flight_info,id']
        ], [
            'id.required' => '参数id缺失',
            'id.numeric'  => '参数id无效',
            'id.exists'   => '该信息不存在',
        ]);
        $ret = $this->flightInfoService->getInfo($params['id']);
        return $this->success($ret);
    }

    /**
     * 添加
     * User：caogang
     * DateTime：2021/5/28 16:01
     * @return array
     */
    public function create()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'flight_name'         => ['required', Rule::unique('flight_info')],
            'start_airports_code' => ['required', 'size:3', 'exists:air_airports,air_port'],
            'end_airports_code'   => ['required', 'size:3', 'exists:air_airports,air_port'],
            'start_time'          => ['required', 'date_format:H:i'],
            'end_time'            => ['required', 'date_format:H:i'],
            'price'               => ['required', 'numeric', 'gte:0'],
            'stock'               => ['required', 'numeric', 'gte:0'],
            'limit_nums'          => ['required', 'numeric', 'gte:0'],
        ], [
            'flight_name.required'         => '航班号不能为空',
            'flight_name.unique'           => '航班号已存在',
            'start_airports_code.required' => '起飞机场三字码不能为空',
            'end_airports_code.size'       => '起飞机场三字码必须为3位字母',
            'start_city_code.exists'       => '起飞机场三字码对应的城市不存在',
            'end_city_code.required'       => '到达机场三字码不能为空',
            'end_city_code.size'           => '到达机场三字码必须为3位字母',
            'end_city_code.exists'         => '到达机场三字码对应的城市不存在',
            'start_time.required'          => '起飞时间不能为空',
            'start_time.date_format'       => '起飞时间格式不正确,正确格式13:30',
            'end_time.required'            => '到达时间不能为空',
            'end_time.date_format'         => '起飞时间格式不正确,正确格式13:30',
            'price.required'               => '价格不能为空',
            'price.numeric'                => '价格必须为数字',
            'price.gte'                    => '价格必须大于等于0',
            'stock.required'               => '库存不能为空',
            'stock.numeric'                => '库存必须为数字',
            'stock.gte'                    => '库存必须大于等于0',
            'limit_nums.required'          => '限购库存不能为空',
            'limit_nums.numeric'           => '限购库存必须为数字',
            'limit_nums.gte'               => '限购库存必须大于等于0',
        ]);

        $ret = $this->flightInfoService->create($params);
        $logMsg = "添加航班信息";
        if ($ret) {
            $this->logService->createLog($logMsg);
            return $this->success();
        } else {
            $this->logService->createLog($logMsg, 0);
            return $this->error(ErrorCode::CREATE_ERROR);
        }
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/5/28 16:04
     * @return array
     */
    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'                  => ['required', 'numeric'],
            'flight_name'         => ['required', Rule::unique('flight_info')->ignore($params['id'], 'id')],
            'start_airports_code' => ['required', 'size:3', 'exists:air_airports,air_port'],
            'end_airports_code'   => ['required', 'size:3', 'exists:air_airports,air_port'],
            'start_time'          => ['required', 'date_format:H:i'],
            'end_time'            => ['required', 'date_format:H:i'],
            'price'               => ['required', 'numeric', 'gte:0'],
            'stock'               => ['required', 'numeric', 'gte:0'],
            'limit_nums'          => ['required', 'numeric', 'gte:0'],
        ], [
            'id.required'                  => '参数id不能为空',
            'id.numeric'                   => '参数id必须为数字',
            'flight_name.required'         => '航班号不能为空',
            'flight_name.unique'           => '航班号已存在',
            'start_airports_code.required' => '起飞机场三字码不能为空',
            'start_airports_code.size'     => '起飞机场三字码必须为3位字母',
            'start_airports_code.exists'   => '起飞机场三字码对应的城市不存在',
            'end_airports_code.required'   => '到达机场三字码不能为空',
            'end_airports_code.size'       => '到达机场三字码必须为3位字母',
            'end_airports_code.exists'     => '到达机场三字码对应的城市不存在',
            'start_time.required'          => '起飞时间不能为空',
            'start_time.date_format'       => '起飞时间格式不正确,正确格式13:30',
            'end_time.required'            => '到达时间不能为空',
            'end_time.date_format'         => '起飞时间格式不正确,正确格式13:30',
            'price.required'               => '价格不能为空',
            'price.numeric'                => '价格必须为数字',
            'price.gte'                    => '价格必须大于等于0',
            'stock.required'               => '库存不能为空',
            'stock.numeric'                => '库存必须为数字',
            'stock.gte'                    => '库存必须大于等于0',
            'limit_nums.required'          => '限购库存不能为空',
            'limit_nums.numeric'           => '限购库存必须为数字',
            'limit_nums.gte'               => '限购库存必须大于等于0',
        ]);
        $ret = $this->flightInfoService->update($params['id'], $params);
        $logMsg = "修改航班信息";
        if ($ret) {
            $this->logService->createLog($logMsg);
            return $this->success();
        } else {
            $this->logService->createLog($logMsg, 0);
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 删除
     * User：caogang
     * DateTime：2021/5/28 16:07
     * @return array
     */
    public function delete()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id' => ['required', 'numeric']
        ], [
            'id.required' => '参数id不能为空',
            'id.numeric'  => '参数id只能为数字',
        ]);
        $ret = $this->flightInfoService->destroy($params['id']);
        $logMsg = '删除航班信息';
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
     * DateTime：2021/5/28 16:07
     * @return array
     */
    public function batchDelete()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'ids' => ['required', 'array']
        ], [
            'ids.required' => '参数id不能为空',
            'ids.numeric'  => '参数id只能为数组',
        ]);
        $ret = $this->flightInfoService->destroy($params['ids']);
        $logMsg = "批量删除航班信息";
        if ($ret) {
            $this->logService->createLog($logMsg, $params);
            return $this->success();
        } else {
            $this->logService->createLog($logMsg, 0);
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    public function getCityName()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'airports' => ['required']
        ], [
            'airports.required' => 'airports不能为空',
        ]);
        $data = $this->flightInfoService->getCityName($params['airports']);
        return ['code' => 0, 'msg' => 'successful', 'data' => $data];
    }

    public function airportsList()
    {
        $list = $this->flightInfoService->airportsList();
        return $this->success($list);
    }

    /**
     * excel导入
     * User：caogang
     * DateTime：2021/6/29 16:33
     */
    public function import()
    {
        $file = request()->file('file');
        $ret = $this->flightInfoService->import($file);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::OPERATE_ERROR);
        }
    }

    /**
     * 文件下载
     * User：caogang
     * DateTime：2021/6/29 16:34
     */
    public function download()
    {
        $url = str_replace(request()->getPathInfo(), " ", request()->url());
        $url = $url . "/excel/航班信息表.xlsx";
        return $this->success($url);
    }

}