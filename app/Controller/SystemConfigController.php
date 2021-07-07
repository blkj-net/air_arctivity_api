<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/27 10:49
 */

namespace App\Controller;


use App\Constants\Common;
use App\Constants\ErrorCode;
use App\Service\SystemConfigService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Rule;

class SystemConfigController extends BaseController
{
    /**
     * @Inject
     * @var SystemConfigService
     */
    protected $configService;

    /**
     * 获取列表
     * User：caogang
     * DateTime：2021/5/27 10:52
     * @return array
     */
    public function list()
    {
        $page = request()->input('page', Common::PAGE);
        $size = request()->input('size', Common::SIZE);
        $where = [];
        $field = ['*'];
        $list = $this->configService->getList($page, $size, $where, $field);
        return $this->success($list);
    }

    /**
     * 添加配置
     * User：caogang
     * DateTime：2021/5/27 13:05
     * @return array
     */
    public function create()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'key'   => ['required', Rule::unique('sys_config', 'key')],
            'value' => 'required'
        ], [
            'key.required'   => 'key值不能为空',
            'key.unique'     => 'key值已存在',
            'value.required' => 'value值不能为空',
        ]);
        $ret = $this->configService->create($params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::CREATE_ERROR);
        }
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/5/27 13:22
     * @return array
     */
    public function update()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id'    => ['required', 'numeric'],
            'key'   => ['required', Rule::unique('sys_config')->ignore($params['id'], 'id')],
            'value' => 'required'
        ], [
            'id.required'    => 'id字段必须',
            'key.required'   => 'key值不能为空',
            'key.unique'     => 'key值已存在',
            'value.required' => 'value值不能为空',
        ]);
        $ret = $this->configService->update($params['id'], $params);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::UPDATE_ERROR);
        }
    }

    /**
     * 删除
     * User：caogang
     * DateTime：2021/5/27 13:24
     */
    public function delete()
    {
        $params = request()->all();
        $this->validatorParams($params, [
            'id' => ['required', 'numeric'],
        ], [
            'id.required' => 'id字段必须',
        ]);
        $ret = $this->configService->destroy($params['id']);
        if ($ret) {
            return $this->success();
        } else {
            return $this->error(ErrorCode::DELETE_ERROR);
        }
    }

}