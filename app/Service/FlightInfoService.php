<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/28 15:37
 */

namespace App\Service;


use App\Exception\BusinessException;
use App\Model\Airports;
use App\Model\FlightInfo;
use App\Model\FlightStock;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class FlightInfoService extends BaseService
{
    /**
     * @Inject
     * @var FlightInfo
     */
    protected $flightInfoModel;
    /**
     * @Inject
     * @var Airports
     */
    protected $airportModel;

    /**
     * 获取列表
     * User：caogang
     * DateTime：2021/5/28 15:38
     * @param $page
     * @param $size
     * @param array $where
     * @param array|string[] $field
     */
    public function getList($page, $size, array $where = [], array $field = ['*']): array
    {
        $list = $this->flightInfoModel->where($where)
            ->orderBy('id', 'desc')
            ->paginate($size, $field, 'page', $page)
            ->toArray();
        return $list;
    }

    /**
     * 所有数据列表
     * User：caogang
     * DateTime：2021/5/28 16:19
     * @return array
     */
    public function selectList(): array
    {
        $list = $this->flightInfoModel->orderBy('id', 'desc')->get()->toArray();
        return $list;
    }

    public function getInfo(int $id)
    {
        return $this->flightInfoModel->find($id)->toArray();
    }

    /**
     * 添加
     * User：caogang
     * DateTime：2021/5/28 15:44
     * @param array $params
     * @return FlightInfo|\Hyperf\Database\Model\Model
     */
    public function create(array $params)
    {
        $params['start_airports_code'] = strtoupper($params['start_airports_code']);
        $params['end_airports_code'] = strtoupper($params['end_airports_code']);
        $params['start_city_name'] = $this->airportModel->getCityByAirport($params['start_airports_code']);
        $params['end_city_name'] = $this->airportModel->getCityByAirport($params['end_airports_code']);
        return $this->flightInfoModel->create($params);
    }

    /**
     * 更新
     * User：caogang
     * DateTime：2021/5/28 15:44
     * @param $id
     * @param array $params
     * @return bool|int
     */
    public function update($id, array $params)
    {
        $params['start_airports_code'] = strtoupper($params['start_airports_code']);
        $params['end_airports_code'] = strtoupper($params['end_airports_code']);
        $params['start_city_name'] = $this->airportModel->getCityByAirport($params['start_airports_code']);
        $params['end_city_name'] = $this->airportModel->getCityByAirport($params['end_airports_code']);
        return $this->flightInfoModel->where('id', $id)->update(FlightInfo::fillableFromArray($params));
    }

    /**
     * 删除
     * User：caogang
     * DateTime：2021/5/28 15:45
     * @param int|array $id
     * @return int
     */
    public function destroy($id)
    {
        return $this->flightInfoModel->destroy($id);
    }

    /**
     *
     * User：caogang
     * DateTime：2021/6/9 14:26
     * @param $airport
     */
    public function getCityName($airport)
    {
        return $this->airportModel->getCityByAirport($airport);
    }

    public function airportsList()
    {
        return $this->airportModel
            ->select([
                'air_port',
                'air_port_name',
                'air_port_ename',
                'city_name',
                'city_ename'
            ])
            ->where('country_code', 'CN')
            ->get()->toArray();
    }

    public function import($file)
    {
        DB::beginTransaction();
        try {
            $path = __DIR__ . '/storage/uploads';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $path = $path . '/' . $file->getClientFilename();
            $file->moveTo($path);
            $spreadsheet = IOFactory::load($path);
            //读取默认工作表
            $worksheet = $spreadsheet->getSheet(0);
            //取得一共有多少行
            $allRow = $worksheet->getHighestRow();
            // 检测数据
            $this->checkImportData($spreadsheet, $allRow);
            //清空数据表
            for ($i = 2; $i <= $allRow; $i++) {
                // 航班数据
                $flightData = [
                    'flight_name'         => $spreadsheet->getActiveSheet()->getCell('A' . $i)->getValue(),
                    'start_airports_code' => $spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue(),
                    'end_airports_code'   => $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue(),
                    'start_city_name'     => $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue(),
                    'end_city_name'       => $spreadsheet->getActiveSheet()->getCell('E' . $i)->getValue(),
                    'start_time'          => $spreadsheet->getActiveSheet()->getCell('G' . $i)->getFormattedValue(),
                    'end_time'            => $spreadsheet->getActiveSheet()->getCell('H' . $i)->getFormattedValue(),
                    'price'               => $spreadsheet->getActiveSheet()->getCell('I' . $i)->getValue(),
                    'stock'               => $spreadsheet->getActiveSheet()->getCell('J' . $i)->getValue(),
                    'limit_nums'          => $spreadsheet->getActiveSheet()->getCell('K' . $i)->getValue(),
                    'sale_start_time'     => $spreadsheet->getActiveSheet()->getCell('L' . $i)->getFormattedValue(),
                    'sale_end_time'       => $spreadsheet->getActiveSheet()->getCell('M' . $i)->getFormattedValue(),
                ];
                $flightData['start_airports_code'] = strtoupper($flightData['start_airports_code']);
                $flightData['end_airports_code'] = strtoupper($flightData['end_airports_code']);
                $flight_info_id = FlightInfo::query()->updateOrCreate([
                    'flight_name'         => $flightData['flight_name'],
                    'start_airports_code' => $flightData['start_airports_code'],
                    'end_airports_code'   => $flightData['end_airports_code'],
                    'start_time'          => $flightData['start_time'],
                    'end_time'            => $flightData['end_time'],
                ], $flightData)->id;
                $date = $spreadsheet->getActiveSheet()->getCell('F' . $i)->getFormattedValue();
                // 库存数据
                $stockData = [
                    'start_city'          => $flightData['start_city_name'],
                    'end_city'            => $flightData['end_city_name'],
                    'start_airports_code' => $flightData['start_airports_code'],
                    'end_airports_code'   => $flightData['end_airports_code'],
                    'original_price'      => $flightData['price'],
                    'price'               => $flightData['price'],
                    'stock'               => $flightData['stock'],
                    'limit_nums'          => $flightData['limit_nums'],
                    'flight_name'         => $flightData['flight_name'],
                    'start_time'          => $date . ' ' . $flightData['start_time'],
                    'end_time'            => $date . ' ' . $flightData['end_time'],
                    'flight_info_id'      => $flight_info_id,
                    'sale_start_time'     => $flightData['sale_start_time'],
                    'sale_end_time'       => $flightData['sale_end_time'],
                ];
                $week = Carbon::parse($stockData['start_time'])->dayOfWeek;
                $stockData['schedule'] = $week == 0 ? 7 : $week;
                FlightStock::query()->updateOrCreate([
                    'flight_info_id' => $stockData['flight_info_id'],
                    'start_time'     => $stockData['start_time'],
                    'end_time'       => $stockData['end_time'],
                ], $stockData);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new BusinessException(10000, $exception->getMessage());
        }
        return true;
    }

    // 检测导入数据
    public function checkImportData($spreadsheet, $allRow)
    {
        for ($i = 2; $i <= $allRow; $i++) {

            $data = [
                'flight_name'         => $spreadsheet->getActiveSheet()->getCell('A' . $i)->getValue(),
                'start_airports_code' => $spreadsheet->getActiveSheet()->getCell('B' . $i)->getValue(),
                'end_airports_code'   => $spreadsheet->getActiveSheet()->getCell('C' . $i)->getValue(),
                'start_city_name'     => $spreadsheet->getActiveSheet()->getCell('D' . $i)->getValue(),
                'end_city_name'       => $spreadsheet->getActiveSheet()->getCell('E' . $i)->getValue(),
                'start_date'          => $spreadsheet->getActiveSheet()->getCell('F' . $i)->getFormattedValue(),
                'start_time'          => $spreadsheet->getActiveSheet()->getCell('G' . $i)->getFormattedValue(),
                'end_time'            => $spreadsheet->getActiveSheet()->getCell('H' . $i)->getFormattedValue(),
                'price'               => $spreadsheet->getActiveSheet()->getCell('I' . $i)->getValue(),
                'stock'               => $spreadsheet->getActiveSheet()->getCell('J' . $i)->getValue(),
                'limit_nums'          => $spreadsheet->getActiveSheet()->getCell('K' . $i)->getValue(),
                'sale_start_time'     => $spreadsheet->getActiveSheet()->getCell('L' . $i)->getFormattedValue(),
                'sale_end_time'       => $spreadsheet->getActiveSheet()->getCell('M' . $i)->getFormattedValue(),
            ];
            // 验证数据
            if (!$data['flight_name']) {
                throw new BusinessException(10000, "航班号不能为空，第{$i}行");
            }
            if ($data['start_airports_code'] == $data['end_airports_code']) {
                throw new BusinessException(10000, "起飞机场三字码和到达机场三字码不能一样，第{$i}行");
            }
            $s_code = Airports::getInfoByPort($data['start_airports_code']);
            if (!$s_code) {
                throw new BusinessException(10000, "起飞机场三字码不存在，第{$i}行");
            }

            $e_code = Airports::getInfoByPort($data['end_airports_code']);
            if (!$e_code) {
                throw new BusinessException(10000, "到达机场三字码不存在，第{$i}行");
            }
            if (!$data['start_city_name']) {
                throw new BusinessException(10000, "起飞城市不能为空，第{$i}行");
            }
            if (!$data['end_city_name']) {
                throw new BusinessException(10000, "达到城市不能为空，第{$i}行");
            }
            if (!$data['start_date'] || !isDateValid($data['start_date'])) {
                throw new BusinessException(10000, "出发日期格式不正确，格式为：2021-06-30，第{$i}行");
            }
            if (!$data['start_time']) {
                throw new BusinessException(10000, "起飞时间不能为空，第{$i}行");
            }
            if (!$data['end_time']) {
                throw new BusinessException(10000, "到达时间不能为空，第{$i}行");
            }
            if (!$data['price'] || !is_numeric($data['price'])) {
                throw new BusinessException(10000, "价格不能为空，必须为数字，第{$i}行");
            }
            if (!$data['stock'] || !is_numeric($data['stock'])) {
                throw new BusinessException(10000, "库存不能为空，必须为数字，第{$i}行");
            }
            if (!$data['limit_nums'] || !is_numeric($data['limit_nums'])) {
                throw new BusinessException(10000, "限购库存不能为空，必须为数字，第{$i}行");
            }
        }
    }


    /**
     * 导出
     * User：caogang
     * DateTime：2021/6/30 11:04
     */
    public function excel()
    {
        try {
            $spreadsheet = new Spreadsheet();
            //设置表格
            $spreadsheet->setActiveSheetIndex(0);
            //设置表头
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', '序号')
                ->setCellValue('B1', '姓名')
                ->setCellValue('C1', '编号')
                ->setCellValue('D1', '单位')
                ->setCellValue('E1', '靶位')
                ->setCellValue('F1', '弹数')
                ->setCellValue('G1', '总成绩')
                ->setCellValue('H1', '靶型')
                ->setCellValue('I1', '射击时间');
            //设置表头居中
            $spreadsheet->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            //设置表格宽度
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);


            //查询数据
            $rows = ScoreModel::create()
                ->join('target_type as type', 'type.target_type_id = score.score_target_type')
                ->field([
                    'score.*',
                    'type.target_type_name'
                ])
                ->all();
            //遍历数据
            foreach ($rows as $i => $row) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . ($i + 2), ($i + 1));
                $spreadsheet->getActiveSheet()->setCellValue('B' . ($i + 2), $row->score_user_name);
                $spreadsheet->getActiveSheet()->setCellValue('C' . ($i + 2), $row->score_user_num);
                $spreadsheet->getActiveSheet()->setCellValue('D' . ($i + 2), $row->score_user_unit);
                $spreadsheet->getActiveSheet()->setCellValue('E' . ($i + 2), $row->score_target_name);
                $spreadsheet->getActiveSheet()->setCellValue('F' . ($i + 2), $row->score_count);
                $spreadsheet->getActiveSheet()->setCellValue('G' . ($i + 2), $row->score_sum);
                $spreadsheet->getActiveSheet()->setCellValue('H' . ($i + 2), $row->target_type_name);
                $spreadsheet->getActiveSheet()->setCellValue('I' . ($i + 2), date('Y-m-d H:i:s', $row->start_time));
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            //设置filename
            $filename = '成绩名单-' . date('Ymd') . '.xls';
            //保存
            $writer->save($filename);

            //swoole下载文件，使用response输出
            $this->response()->write(file_get_contents($filename));
            $this->response()->withHeader('Content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $this->response()->withHeader('Content-Disposition', 'attachment;filename=' . $filename);
            $this->response()->withHeader('Cache-Control', 'max-age=0');
            $this->response()->end();

            $this->writeJson(Status::CODE_OK, null, '导出成功');
        } catch (\Throwable $throwable) {
            $this->writeJson(Status::CODE_BAD_REQUEST, null, $throwable->getMessage());
        }
    }


}