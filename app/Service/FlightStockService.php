<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/30 11:38
 */

namespace App\Service;


use App\Exception\BusinessException;
use App\Model\FlightStock;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class FlightStockService extends BaseService
{

    /**
     * @Inject
     * @var FlightStock
     */
    protected $flightStockModel;

    public function getList($page, $size, array $where = [], $field = ['*'])
    {
        $list = $this->flightStockModel
            ->where($where)
            ->orderBy('id', 'desc')
            ->paginate($size, $field, 'page', $page)
            ->toArray();
        return $list;
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/6/2 15:53
     * @param $id
     * @param array $params
     * @return int
     */
    public function update($id, array $params)
    {
        $arr = [
            'original_price'  => $params['original_price'],
            'price'           => $params['price'],
            'discount_amount' => $params['discount_amount'],
            'limit_nums'      => $params['limit_nums'],
        ];
        if (isset($params['remark'])) {
            $arr['remark'] = $params['remark'];
        }
        return FlightStock::query()->where('id', $id)->update($arr);
    }

    /**
     * 创建库存
     * User：caogang
     * DateTime：2021/5/30 11:46
     * @param array $params
     */
    public function createStock(array $params)
    {
        $this->flightStockModel->updateOrCreate($params, $params);
        return true;
    }

    /**
     * 加或减库存
     * User：caogang
     * DateTime：2021/6/24 11:22
     * @param $id
     * @param $type
     * @param $nums
     * @return bool
     */
    public function addOrSubStock($id, $type, $nums)
    {
        DB::beginTransaction();
        try {
            $stock = $this->flightStockModel->lockForUpdate()->find($id);
            // 删除redis中的对应库存
            redis('stock')->del(self::STOCK_REDIS_PREFIX . $id);
            if ($type == 'sub') { // 减库存
                if ($nums > $stock->stock) {
                    throw new BusinessException(10000, '库存不足，剩余：' . $stock->stock);
                }
                $stock->decrement('stock', $nums);
            } else if ($type == 'add') { // 加库存
                $stock->increment('stock', $nums);
            } else {
                throw new BusinessException(10000, '操作类型错误');
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new BusinessException(10000, $exception->getMessage());
        }
        return true;
    }

    // 加库存
    public function incrStock($id, $number)
    {
        return $this->flightStockModel->where('id', $id)->increment('stock', $number);
    }

    // 减库存
    public function decrStock($id, $number)
    {
        return $this->flightStockModel->where('id', $id)->decrement('stock', $number);
    }
}