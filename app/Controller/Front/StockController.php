<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/31 9:51
 */

namespace App\Controller\Front;


use App\Constants\ErrorCode;
use App\Constants\OrderStatus;
use App\Exception\BusinessException;
use App\Model\FlightAirportHotel;
use App\Model\FlightHotel;
use App\Service\Front\StockService;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class StockController extends BaseController
{
    /**
     * @Inject
     * @var StockService
     */
    protected $stockService;

    public function homeList()
    {
        $page = request()->input('page', 1);
        $size = request()->input('size', 10);
        $where = [];
        $field = "id,start_city,end_city,price,stock,start_time,start_airports_code,end_airports_code";
        [$list, $total] = $this->stockService->getList($page, $size, $field, $where);
        $listArray = [];
        foreach ($list as $k => $v) {
            $listArray[$k]['id'] = $v->id;
            $listArray[$k]['start_city'] = $v->start_city;
            $listArray[$k]['end_city'] = $v->end_city;
            $listArray[$k]['start_airports_code'] = $v->start_airports_code;
            $listArray[$k]['end_airports_code'] = $v->end_airports_code;
            $listArray[$k]['price'] = $v->price;
            $listArray[$k]['stock'] = $v->stock;
            $listArray[$k]['start_time'] = Carbon::parse($v->start_time)->format('m-d');
            $listArray[$k]['week'] = Carbon::parse($v->start_time)->dayOfWeek;
        }
        return $this->success(['data' => $listArray, 'total' => $total]);
    }

    /**
     * 详情列表
     * User：caogang
     * DateTime：2021/5/31 11:40
     */
    public function detailsList()
    {
        $id = request()->input('id');
        if (!$id) {
            return $this->error(ErrorCode::PARAMS_LOSE);
        }
        $list = $this->stockService->detailsList($id);
        return $this->success($list);
    }

    /**
     * 获取酒店信息
     * User：caogang
     * DateTime：2021/6/28 16:50
     * @return array
     */
    public function getHotel()
    {
        $air_ports = request()->input('air_ports');
        $url = request()->url();
        $path = request()->getPathInfo();
        $url = str_replace($path, '', $url);
        if (!$air_ports) {
            throw new BusinessException(10000, '缺少参数');
        }
        $hotelIds = FlightAirportHotel::query()->where('air_port', $air_ports)->pluck('hotel_id');
        if ($hotelIds) {
            $hotelIds = $hotelIds->toArray();
        } else {
            $hotelIds = [];
        }
        $list = FlightHotel::query()->whereIn('id', $hotelIds)->get();
        if ($list) {
            foreach ($list as &$item) {
                $item['base_info'] = explode(',', $item['base_info']);
                $item['amenities'] = explode(',', $item['amenities']);
                $item['notice'] = json_decode($item['notice'], true);
                // 组装图片数据
                $images = explode(',', $item['images']);
                foreach ($images as &$image) {
                    $image = $url . '/' . $image;
                }
                $item['images'] = $images;
                $item['details'] = [
                    'info'   => $item['introduce'],
                    'notice' => $item['notice'],
                ];
                unset($item['introduce'], $item['notice'], $item['updated_at'], $item['deleted_at']);
            }
            return $this->success($list->toArray());
        } else {
            return $this->success();
        }
    }

    public function createOrder()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'                    => ['required', 'numeric', 'exists:flight_stock,id'],
            'hotel_id'              => ['required', "exists:flight_hotel,id"],
            'username'              => ['required', 'regex:/^[\x{4e00}-\x{9FA5}A-Za-z][\x{4e00}-\x{9fa5}A-Za-z\s\.]+$/u'],
            'phone'                 => ['required', 'regex:/^[1][3,4,5,7,8,9][0-9]{9}$/'],
            'user_info'             => ['required', 'array'],
            'user_info.*.username'  => ['required', 'regex:/^[\x{4e00}-\x{9FA5}A-Za-z][\x{4e00}-\x{9fa5}A-Za-z\s\.]+$/u'],
            'user_info.*.idcard_no' => ['required', 'regex:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/'],
            'user_info.*.phone'     => ['required', 'regex:/^[1][3,4,5,7,8,9][0-9]{9}$/'],
            'uid'                   => 'required'
        ], [
            'id.required'                    => '缺少参数id',
            'id.numeric'                     => '参数id无效',
            'id.exists'                      => '数据不存在',
            'hotel_id.required'              => '酒店ID必须',
            'hotel_id.exists'                => '酒店不存在',
            'username.required'              => '联系人姓名不能为空',
            'username.regex'                 => '联系人姓名格式只能为汉字或字母',
            'phone.required'                 => '联系人手机号不能为空',
            'phone.regex'                    => '联系人手机号格式不正确',
            'user_info.required'             => '请填写用户信息',
            'user_info.array'                => '用户信息格式不正确',
            'user_info.*.username.required'  => '请填写用户信息',
            'user_info.*.username.regex'     => '姓名格式只能为汉字或字母',
            'user_info.*.idcard_no.required' => '请填写身份证号码',
            'user_info.*.idcard_no.regex'    => '身份证号码格式不正确',
            'user_info.*.phone.required'     => '请填写手机号码',
            'user_info.*.phone.regex'        => '手机号码格式不正确',
            'uid'                            => '用户ID不能为空'
        ]);
        $ret = $this->stockService->createOrder($params['id'], $params);
        if ($ret) {
            return $this->success($ret);
        } else {
            throw new BusinessException(10000, '下单失败');
        }
    }

    /**
     * 获取订单信息
     * User：caogang
     * DateTime：2021/6/23 15:15
     */
    public function orderInfo()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'order_no' => 'required'
        ]);
        return $this->success($this->stockService->orderInfo($params['order_no']));
    }

    /**
     * 查询是否存在待支付的订单
     * User：caogang
     * DateTime：2021/6/23 17:19
     */
    public function waitPayOrder()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'uid' => 'required'
        ]);
        return $this->success($this->stockService->waitPayOrder($params['uid']));
    }

    public function orderList()
    {
        $status = request()->input('status', 0);
        $uid = request()->input('uid');
        $page = request()->input('page', 1);
        $size = request()->input('size', 10);
        if (!$uid) {
            throw new BusinessException(10000, '缺少用户标识');
        }
        $where = [];
        $where[] = ['uid', '=', $uid];
        if (is_numeric($status)) {
            if ($status == 1) {
                $where[] = ['status', '=', $status];
            }
            if ($status == 2) {
                $where[] = ['status', '=', $status];
            }
            if ($status == 3) {
                $where[] = ['status', '=', OrderStatus::ORDER_REFUND_ING];
            }
            if ($status == 4) {
                $where = function ($query) use ($where, $status) {
                    $query->where($where)->whereIn('status', [
                        OrderStatus::ORDER_PAY_EXPIRED,
                        OrderStatus::ORDER_COMPLETE,
                        OrderStatus::ORDER_DISCARD,
                        OrderStatus::ORDER_CANCEL,
                        OrderStatus::ORDER_REFUND,
                        OrderStatus::ORDER_REFUND_FAIL,
                    ]);
                };
            }
        }

        $list = $this->stockService->getOrderList($page, $size, $where);
        return $this->success($list);
    }

    /**
     * 取消订单
     * User：caogang
     * DateTime：2021/6/28 17:44
     */
    public function orderCancel()
    {
        $order_no = request()->input('order_no');
        $ret = $this->stockService->orderCancel($order_no);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::OPERATE_ERROR);
        }
    }

    /**
     * 订单退款
     * User：caogang
     * DateTime：2021/6/29 15:23
     * @return array
     */
    public function orderRefund()
    {
        $order_no = request()->input('order_no');
        $ret = $this->stockService->orderRefund($order_no);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::OPERATE_ERROR);
        }
    }
}