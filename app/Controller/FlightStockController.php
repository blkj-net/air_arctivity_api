<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/30 20:50
 */

namespace App\Controller;


use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Service\FlightStockService;
use Carbon\Carbon;
use Hyperf\Di\Annotation\Inject;

class FlightStockController extends BaseController
{
    /**
     * @Inject
     * @var FlightStockService
     */
    protected $flightStockService;

    /**
     * 获取库存列表
     * User：caogang
     * DateTime：2021/5/30 20:53
     * @return array
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $flightName = request()->input('flight_name');
        $startAirportsCode = request()->input('start_airports_code');
        $endAirportsCode = request()->input('end_airports_code');
        $startTime = request()->input('start_time');
        $id = request()->input('id');
        $where = [];
        if ($id) {
            $where[] = ['id', '=', $id];
        }
        // 航班号
        if ($flightName) {
            $where[] = ['flight_name', '=', $flightName];
        }
        // 起飞城市三字码
        if ($startAirportsCode) {
            $where[] = ['start_airports_code', '=', $startAirportsCode];
        }
        // 结束城市三字码
        if ($endAirportsCode) {
            $where[] = ['end_airports_code', '=', $endAirportsCode];
        }
        // 起飞日期筛选
        if ($startTime) {
            $where[] = ['start_time', '>', Carbon::parse($startTime)->format('Y-m-d 00:00:00')];
            $where[] = ['start_time', '<', Carbon::parse($startTime)->format('Y-m-d 23:59:59')];
        }
        $field = ['*'];
        $list = $this->flightStockService->getList($page, $size, $where, $field);
        return $this->success($list);
    }

    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'              => ['required', 'numeric'],
            'original_price'  => ['required', 'numeric'],
            'price'           => ['required', 'numeric'],
            'discount_amount' => ['required', 'numeric'],
            'limit_nums'      => ['required', 'numeric'],
        ], [
            'id.required'              => '参数id必须',
            'id.numeric'               => '参数id必须为数字',
            'original_price.required'  => '原价必须',
            'original_price.numeric'   => '原价必须为数字',
            'price.required'           => '现价必须',
            'price.numeric'            => '现价必须为数字',
            'discount_amount.required' => '优惠价格必须',
            'discount_amount.numeric'  => '优惠价格必须为数字',
            'limit_nums.required'      => '库存限购必须',
            'limit_nums.numeric'       => '库存限购必须为数字',
        ]);
        $ret = $this->flightStockService->update($params['id'], $params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 加减库存
     * User：caogang
     * DateTime：2021/6/1 17:02
     * @return array
     */
    public function subStock()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'   => ['required', 'numeric'],
            'type' => ['required'],
            'nums' => ['required', 'numeric'],
        ], [
            'id.required'   => '缺少参数id',
            'id.numeric'    => '参数id必须为数字',
            'type.required' => '操作类型必须',
            'nums.required' => '库存数量不能为空',
            'nums.numeric'  => '库存数量必须为数字',
        ]);

        $ret = $this->flightStockService->addOrSubStock($params['id'],$params['type'], $params['nums']);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}