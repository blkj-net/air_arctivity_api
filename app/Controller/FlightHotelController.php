<?php
/**
 * 酒店管理
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/28 13:10
 */

namespace App\Controller;


use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Libs\Images;
use App\Model\Airports;
use App\Model\FlightAirportHotel;
use App\Service\FlightHotelService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Rule;
use League\Flysystem\Filesystem;

class FlightHotelController extends BaseController
{

    /**
     * @Inject
     * @var FlightHotelService
     */
    protected $flightHotelService;

    /**
     * @Inject
     * @var Filesystem
     */
    protected $filesystem;

    /**列表
     * User：caogang
     * DateTime：2021/6/28 13:11
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $hotelName = request()->input('hotel_name');
        $cityName = request()->input('city_name');
        $where = [];
        if ($hotelName) {
            $where[] = ['name', 'like', "%{$hotelName}%"];
        }
        if ($cityName) {
            // 获取三字码列表
            $airPorts = Airports::query()->where('city_name', 'like', "%{$cityName}%")->pluck('air_port');
            // 查找hotel_ids
            $hotelIds = FlightAirportHotel::query()->whereIn('air_port', $airPorts)->pluck('hotel_id');
            if (!$hotelIds) {
                $where[] = ['air_ports', '=', 'AAAA'];
            } else {
                $where = function ($query) use ($where, $hotelIds) {
                    $query->where($where)->whereIn('id', $hotelIds);
                };
            }
        }
        $list = $this->flightHotelService->getList($page, $size, $where);
        return $this->success($list);
    }

    /**
     * 添加
     * User：caogang
     * DateTime：2021/6/28 13:11
     */
    public function create()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'name'            => ['required', Rule::unique('flight_hotel')],
            'store_name'      => 'required',
            'price'           => ['required', 'numeric', 'gte:0'],
            'discount_amount' => ['required', 'numeric', 'gte:0'],
            'address'         => 'required',
            'base_info'       => 'required',
            'amenities'       => 'required',
            'images'          => 'required',
            'introduce'       => 'required',
            'notice'          => 'required',
            'air_port'        => ['required', "exists:air_airports,air_port"]
        ], [], [
            'name'            => '酒店名称',
            'store_name'      => '店铺名称',
            'price'           => '价格',
            'discount_amount' => '优惠价格',
            'address'         => '酒店地址',
            'base_info'       => '基础信息',
            'amenities'       => '便利设施',
            'images'          => '图片',
            'introduce'       => '酒店介绍',
            'notice'          => '酒店注意事项',
            'air_port'        => '机场三字码'
        ]);
        $ret = $this->flightHotelService->create($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::CREATE_ERROR);
        }
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/6/28 13:12
     */
    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'              => ['required', 'numeric'],
            'name'            => ['required', Rule::unique('flight_hotel')->ignore($params['id'], 'id')],
            'store_name'      => 'required',
            'price'           => ['required', 'numeric', 'gte:0'],
            'discount_amount' => ['required', 'numeric', 'gte:0'],
            'address'         => 'required',
            'base_info'       => 'required',
            'amenities'       => 'required',
            'images'          => 'required',
            'introduce'       => 'required',
            'notice'          => 'required',
            'air_port'        => ['required', "exists:air_airports,air_port"]
        ], [], [
            'id'              => '参数ID',
            'name'            => '酒店名称',
            'store_name'      => '店铺名称',
            'price'           => '价格',
            'discount_amount' => '优惠价格',
            'address'         => '酒店地址',
            'base_info'       => '基础信息',
            'amenities'       => '便利设施',
            'images'          => '图片',
            'introduce'       => '酒店介绍',
            'notice'          => '酒店注意事项',
            'air_port'        => '机场三字码'
        ]);
        $ret = $this->flightHotelService->update($params['id'], $params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 删除
     * User：caogang
     * DateTime：2021/6/28 13:12
     */
    public function delete()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id' => ['required', 'numeric'],
        ]);
        $ret = $this->flightHotelService->destroy($params['id']);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    /**
     * 批量删除
     * User：caogang
     * DateTime：2021/6/28 13:12
     */
    public function batchDelete()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'ids' => ['required', 'array'],
        ]);
        $ret = $this->flightHotelService->destroy($params['ids']);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    /**
     * 图片上传
     * User：caogang
     * DateTime：2021/6/28 14:03
     */
    public function upload(Images $images)
    {
        $file = request()->file('file');
        $ret = $images->upload($file);
        return $this->success($ret);
    }

    /**
     * 图片删除
     * User：caogang
     * DateTime：2021/6/29 10:48
     * @param Images $images
     * @return array
     */
    public function delImg(Images $images)
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'file_name' => ['required']
        ]);
        $ret = $images->delete($params['file_name']);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

    public function flightList()
    {
        $list = $this->flightHotelService->flightList();
        foreach ($list as &$item) {
            $item['label'] = $item['province'];
            $item['value'] = $item['province'];
            unset($item['province']);
            foreach ($item['children'] as &$v) {
                $v['label'] = $v['air_port_name'];
                $v['value'] = $v['air_port'];
                unset($v['air_port_name'], $v['air_port']);
            }
        }
        return $this->success($list);
    }
}