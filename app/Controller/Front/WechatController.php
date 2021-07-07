<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/23 10:00
 */

namespace App\Controller\Front;


use App\Service\Front\WechatService;
use Hyperf\Di\Annotation\Inject;

class WechatController extends BaseController
{


    /**
     * 微信服务
     * @Inject
     * @var WechatService
     */
    protected $wechatService;

    /**
     * 授权
     * User：caogang
     * DateTime：2021/6/23 10:00
     */
    public function oauth()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'code' => 'required'
        ]);
        $data = $this->wechatService->oauth($params['code']);
        return $this->success($data);
    }

    /**
     * 支付
     * User：caogang
     * DateTime：2021/6/23 10:01
     */
    public function pay()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'order_no' => 'required',
        ], [], [
            'order_no' => "订单号",
        ]);
        return $this->wechatService->pay($params['order_no']);
    }

    /**
     * 支付回调
     * User：caogang
     * DateTime：2021/6/23 14:57
     */
    public function callback()
    {
        $params = request()->all();
        return $this->wechatService->payNotify($params);
    }

    /**
     * 支付回调
     * User：caogang
     * DateTime：2021/6/23 14:57
     */
    public function refundCallback()
    {
        $params = request()->all();
        return $this->wechatService->refundNotify($params);
    }

}