<?php
/**
 * 系统配置服务
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/27 10:35
 */

namespace App\Service;

use App\Model\SysConfig;
use Hyperf\Di\Annotation\Inject;

class SystemConfigService extends BaseService
{
    /**
     * @Inject
     * @var SysConfig
     */
    protected $configModel;

    /**
     * 获取列表
     * User：caogang
     * DateTime：2021/5/27 10:48
     * @param $page
     * @param $size
     * @param array $where
     * @param array $field
     * @return array
     */
    public function getList($page, $size, array $where, array $field)
    {
        return $this->configModel->where($where)
            ->paginate($size, $field, 'page', $page)
            ->toArray();
    }

    /**
     * 添加配置
     * User：caogang
     * DateTime：2021/5/27 10:46
     * @param $key
     * @param $value
     * @param $remark
     * @param string $group
     * @return SysConfig|\Hyperf\Database\Model\Model
     */
    public function create(array $params): SysConfig
    {
        return $this->configModel->create($params);
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/5/27 13:09
     * @param $id
     * @param array $params
     * @return bool|int
     */
    public function update($id, array $params)
    {
        return $this->configModel->where('id', $id)->update(SysConfig::fillableFromArray($params));
    }

    /**
     * User：caogang
     * DateTime：2021/5/27 13:25
     * @param int|array $id
     */
    public function destroy($id)
    {
        return $this->configModel->destroy($id);
    }

    /**
     * 根据key获取value值
     * User：caogang
     * DateTime：2021/5/27 10:43
     * @param $key
     * @return \Hyperf\Utils\HigherOrderTapProxy|mixed|void
     */
    public function getValueByKey(string $key): string
    {
        return $this->configModel->where('key', $key)->value('value');
    }

    /**
     * User：caogang
     * DateTime：2021/5/27 10:45
     * @param $group
     * @return array
     */
    public function getValueByGroup(string $group): array
    {
        return $this->configModel->select(['key', 'value', 'remark'])->where('group', $group)->get()->toArray();
    }
}