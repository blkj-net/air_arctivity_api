<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/28 13:13
 */

namespace App\Service;

use App\Exception\BusinessException;
use App\Model\Airports;
use App\Model\FlightAirportHotel;
use App\Model\FlightHotel;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class FlightHotelService extends BaseService
{

    public function getList($page, $size, $where = [], $orderBy = "id desc")
    {
        $url = str_replace(request()->getPathInfo(), '', request()->url());
        $orderBy = explode(' ', $orderBy);
        $list = FlightHotel::query()
            ->where($where)
            ->orderBy($orderBy[0], $orderBy[1])
            ->paginate($size, ['*'], 'page', $page)
            ->toArray();
        foreach ($list['data'] as &$item) {
            $airports = FlightAirportHotel::where('hotel_id', $item['id'])->value('air_port');
            $airports = Airports::getInfoByPort($airports);
            $item['city_name'] = $airports->city_name;
            $item['air_ports_array'] = [
                $airports->province,
                $airports->air_port
            ];
            $item['notice'] = json_decode($item['notice'], true);
            $images = explode(',', $item['images']);
            foreach ($images as &$image) {
                $image = $url . '/' . $image;
            }
            $item['images_str'] = $images;
        }
        return $list;
    }

    public function create(array $params)
    {
        $params['images'] = trim($params['images'], ',');
        $params['notice'] = json_encode($params['notice'], JSON_UNESCAPED_UNICODE);
        DB::beginTransaction();
        try {
            $hotel = FlightHotel::query()->create($params);
            $arr = [
                'hotel_id' => $hotel->id,
                'air_port' => $params['air_port']
            ];
            FlightAirportHotel::query()->updateOrCreate($arr, $arr);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new BusinessException(10000, $exception->getMessage());
        }
        return true;
    }

    public function update($id, $params)
    {
        $params['images'] = trim($params['images'], ',');
        $params['notice'] = json_encode($params['notice'], JSON_UNESCAPED_UNICODE);
        DB::beginTransaction();
        try {
            FlightHotel::query()->where('id', $id)->update(FlightHotel::fillableFromArray($params));
            $arr = [
                'hotel_id' => $id,
                'air_port' => $params['air_port']
            ];
            FlightAirportHotel::query()->updateOrCreate($arr, $arr);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new BusinessException(10000, $exception->getMessage());
        }
        return true;
    }

    public function destroy($id)
    {
        return FlightHotel::destroy($id);
    }

    public function flightList()
    {
        $provinceList = Airports::query()->select('province')->where('country_code', 'CN')->groupBy(['province'])->get()->toArray();
        $wg = new \Hyperf\Utils\WaitGroup();
        foreach ($provinceList as &$province) {
            go(function () use (&$province, $wg) {
                $wg->add(1);
                $province['children'] = Airports::query()->select(['air_port', 'air_port_name'])->where('province', $province['province'])->get()->toArray();
                $wg->done();
            });
        }
        $wg->wait();
        return $provinceList;
    }
}