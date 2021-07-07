<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 16:54
 */

namespace App\Service;


use App\Exception\BusinessException;
use App\Model\FlightConfig;
use App\Model\FlightConfigSchedule;
use App\Model\FlightInfo;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class FlightConfigService extends BaseService
{
    /**
     * @Inject
     * @var FlightConfig
     */
    protected $flightConfigModel;

    /**
     * @Inject
     * @var FlightInfo
     */
    protected $flightInfoModel;

    /**
     * @Inject
     * @var FlightStockService
     */
    protected $flightStockService;

    /**
     * 获取列表
     * User：caogang
     * DateTime：2021/5/28 16:57
     * @param $page
     * @param $size
     * @param array $where
     * @param array|string[] $field
     * @return array
     */
    public function getList($page, $size, array $where = [], array $field = ['*']): array
    {
        $list = $this->flightConfigModel
            ->with(['info:id,flight_name,start_city_name,end_city_name,start_time,end_time,price,stock,limit_nums', 'scheduleList:config_id,schedule,original_price,price,discount_amount'])
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($size, $field, 'page', $page)
            ->toArray();
        return $list;
    }

    /**
     * 添加
     * User：caogang
     * DateTime：2021/5/28 16:58
     * @param array $params
     */
    public function create(array $params)
    {
        DB::beginTransaction();
        try {
            $id = $this->flightConfigModel->create($params)->id;
            foreach ($params['schedule_list'] as $schedule) {
                if ($schedule['discount_amount'] > $schedule['original_price']) {
                    throw new BusinessException(10000, '优惠价格不能大于原价');
                }
                $arr = [
                    'config_id'       => $id,
                    'schedule'        => $schedule['schedule'],
                    'original_price'  => $schedule['original_price'],
                    'price'           => $schedule['original_price'] - $schedule['discount_amount'],
                    'discount_amount' => $schedule['discount_amount'],
                ];
                FlightConfigSchedule::create($arr);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
        return true;
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/5/28 17:00
     * @param $id
     * @param array $params
     * @return bool|int
     */
    public function update($id, array $params)
    {
        DB::beginTransaction();
        try {
            // 删除配置
            FlightConfigSchedule::where('config_id', $id)->delete();
            foreach ($params['schedule_list'] as $schedule) {
                if ($schedule['discount_amount'] > $schedule['original_price']) {
                    throw new BusinessException(10000, '优惠价格不能大于原价');
                }
                $arr = [
                    'config_id'       => $id,
                    'schedule'        => $schedule['schedule'],
                    'original_price'  => $schedule['original_price'],
                    'price'           => $schedule['original_price'] - $schedule['discount_amount'],
                    'discount_amount' => $schedule['discount_amount'],
                ];
                FlightConfigSchedule::create($arr);
            }
            $this->flightConfigModel->where('id', $id)->update(FlightConfig::fillableFromArray($params));
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new BusinessException(10000, $exception->getMessage());
        }
        return true;
    }

    /**
     * 删除
     * User：caogang
     * DateTime：2021/5/28 17:01
     * @param int|array $id
     * @return int
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            if (is_array($id)) {
                FlightConfigSchedule::whereIn('config_id', $id)->delete();
            } else {
                FlightConfigSchedule::where('config_id', $id)->delete();
            }
            $this->flightConfigModel->destroy($id);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
        return true;
    }

    // 获取一段时间内，对应星期几的日期
    public function getWeekDate($start_time, $end_time, array $week)
    {
        $begin = Carbon::make($start_time);
        $end = Carbon::make($end_time);
        $dateArr = $begin->daysUntil($end)->toArray();
        $arr = [];
        foreach ($dateArr as $date) {
            foreach ($week as $v) {
                switch ($v) {
                    case 1:
                        if ($date->isMonday()) {
                            $arr[] = ['date' => $date->format('Y-m-d'), 'week' => 1];
                        }
                        break;
                    case 2:
                        if ($date->isTuesday()) {
                            $arr[] = ['date' => $date->format('Y-m-d'), 'week' => 2];
                        }
                        break;
                    case 3:
                        if ($date->isWednesday()) {
                            $arr[] = ['date' => $date->format('Y-m-d'), 'week' => 3];
                        }
                        break;
                    case 4:
                        if ($date->isThursday()) {
                            $arr[] = ['date' => $date->format('Y-m-d'), 'week' => 4];
                        }
                        break;
                    case 5:
                        if ($date->isFriday()) {
                            $arr[] = ['date' => $date->format('Y-m-d'), 'week' => 5];
                        }
                        break;
                    case 6:
                        if ($date->isSaturday()) {
                            $arr[] = ['date' => $date->format('Y-m-d'), 'week' => 6];
                        }
                        break;
                    case 7:
                        if ($date->isSunday()) {
                            $arr[] = ['date' => $date->format('Y-m-d'), 'week' => 7];
                        }
                        break;
                }
            }
        }
        return $arr;
    }

    /**
     * 创建库存
     * User：caogang
     * DateTime：2021/5/28 17:53
     * @param $config_id
     */
    public function createStock($config_id)
    {
        $configData = $this->flightConfigModel->with('info:*')->find($config_id);
        if (!$configData) {
            throw new BusinessException(10000, '数据不存在');
        }
        // 获取班期列表
        $scheduleList = FlightConfigSchedule::where('config_id', $config_id)->get()->toArray();
        // 获取班期
        $schedule = array_column($scheduleList, 'schedule');
        // 将schedule作为数组的索引
        $scheduleList = array_column($scheduleList, null, 'schedule');
        // 一段时间中，筛选出星期几对应的日期
        $arrDate = $this->getWeekDate($configData->sale_start_time, $configData->sale_end_time, $schedule);
        DB::beginTransaction();
        try {
            foreach ($arrDate as $date) {
                $startTime = $date['date'] . " " . $configData->info->start_time;
                if ($configData->info->start_time > $configData->info->end_time) {
                    $endTime = Carbon::parse($date['date'])->addDay()->format('Y-m-d') . " " . $configData->info->start_time;
                } else {
                    $endTime = $date['date'] . " " . $configData->info->end_time;
                }
                // 结束售卖时间----根据航班的起飞时间算
                $saleStartTime = Carbon::parse($startTime)->subHours($configData->flight_hours_ago)->format('Y-m-d H:i:s');
                $arr = [
                    'start_city'          => $configData->info->start_city_name,
                    'end_city'            => $configData->info->end_city_name,
                    'start_airports_code' => $configData->info->start_airports_code,
                    'end_airports_code'   => $configData->info->end_airports_code,
                    'original_price'      => $scheduleList[$date['week']]['original_price'],
                    'price'               => $scheduleList[$date['week']]['price'],
                    'discount_amount'     => $scheduleList[$date['week']]['discount_amount'],
                    'stock'               => $configData->info->stock,
                    'limit_nums'          => $configData->info->limit_nums,
                    'flight_name'         => $configData->info->flight_name,
                    'flight_config_id'    => $configData->id,
                    'flight_info_id'      => $configData->flight_info_id,
                    'start_time'          => $startTime,
                    'end_time'            => $endTime,
                    'sale_start_time'     => $configData->sale_start_time,
                    'sale_end_time'       => $saleStartTime,
                    'schedule'            => $date['week'],
                ];
                $this->flightStockService->createStock($arr);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new BusinessException(10000, $e->getMessage());
        }

        return true;
    }
}