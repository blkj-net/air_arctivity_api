<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/31 16:12
 */

namespace App\Controller;


use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Service\FlightOrderService;
use Carbon\Carbon;
use Hyperf\Di\Annotation\Inject;

class FlightOrderController extends BaseController
{
    /**
     * @Inject
     * @var FlightOrderService
     */
    protected $orderService;

    /**
     * 订单列表
     * User：caogang
     * DateTime：2021/5/31 16:51
     * @return array
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $flightName = request()->input('flight_name');
        $orderNo = request()->input('order_no');
        $phone = request()->input('phone');
        $username = request()->input('username');
        $status = request()->input('status');
        $where = [];
        if (is_numeric($status)) {
            if ($status != 0) {
                $where[] = ['status', '=', $status];
            }
        }
        // 航班号
        if ($flightName) {
            $where[] = ['flight_name', '=', $flightName];
        }
        // 订单号
        if ($orderNo) {
            $where[] = ['order_no', '=', $orderNo];
        }
        // 手机号码
        if ($phone) {
            $where[] = ['phone', '=', $phone];
        }
        // 姓名
        if ($username) {
            $where[] = ['username', 'like', "%{$username}%"];
        }
        $field = ['*'];
        $list = $this->orderService->getList($page, $size, $where, $field);
        return $this->success($list);
    }

    /**
     * 订单详情
     * User：caogang
     * DateTime：2021/5/31 16:51
     * @return array
     */
    public function info()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id' => ['required', 'numeric', 'exists:flight_order,id']
        ], [
            'id.required' => '参数id必须',
            'id.numeric'  => '参数id必须为数字',
            'id.exists'   => '数据不存在',
        ]);
        $details = $this->orderService->details($params['id']);
        return $this->success($details);
    }

    /**
     * 订单锁定、解锁
     * User：caogang
     * DateTime：2021/5/31 17:15
     * @return array
     */
    public function lock()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'      => ['required', 'numeric', 'exists:flight_order,id'],
            'is_lock' => ['required', 'numeric', 'in:0,1']
        ], [
            'id.required' => '参数id必须',
            'id.numeric'  => '参数id必须为数字',
            'id.exists'   => '数据不存在',
        ]);
        $ret = $this->orderService->lock($params['id'], $params['is_lock']);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 订单编辑
     * User：caogang
     * DateTime：2021/5/31 17:34
     */
    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'                          => ['required', 'numeric'],
            'status'                      => ['required', 'numeric', 'in:1,2,3,4,5'],
            'is_lock'                     => ['required', 'numeric', 'in:0,1'],
            'user_info'                   => ['required', 'array'],
            'user_info.*.username'        => ['required'],
            'user_info.*.idcard_no'       => ['required', 'regex:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/'],
            'user_info.*.phone'           => ['required', 'regex:/^[1][3,4,5,7,8,9][0-9]{9}$/'],
            'user_info.*.flight_stock_id' => ['required', 'numeric'],
            'user_info.*.order_id'        => ['required', 'numeric'],
        ], [
            'id.required'                    => '缺少参数id',
            'id.numeric'                     => '参数id无效',
            'status.required'                => '订单状态必须',
            'status.numeric'                 => '订单状态必须为数字',
            'status.in'                      => '订单状态无效',
            'is_lock.required'               => '请选择是否锁定',
            'is_lock.numeric'                => '上锁参数必须为数字',
            'is_lock.in'                     => '上锁参数无效',
            'user_info.required'             => '请填写用户信息',
            'user_info.array'                => '用户信息格式不正确',
            'user_info.*.username.required'  => '请填写用户信息',
            'user_info.*.idcard_no.required' => '请填写身份证号码',
            'user_info.*.idcard_no.regex'    => '身份证号码格式不正确',
            'user_info.*.phone.required'     => '请填写手机号码',
            'user_info.*.phone.regex'        => '手机号码格式不正确',
        ]);
        $ret = $this->orderService->update($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    public function audit()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'order_no'      => ['required'],
            'type'          => ['required', 'in:agree,refuse'],
            'refund_amount' => ['required', 'numeric']
        ]);
        $ret = $this->orderService->refundAudit($params['order_no'], $params['type'], $params['refund_amount']);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::OPERATE_ERROR);
        }
    }

}