<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/28 15:02
 */

namespace App\Libs;


use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use League\Flysystem\Filesystem;

class Images
{
    /**
     * @Inject
     * @var Filesystem
     */
    protected $filesystem;

    public function upload($file, $path = 'images/hotel/')
    {
        $stream = fopen($file->getRealPath(), 'r+');
        $fileName = date("YmdHis") . rand(1000, 9999) . '.jpg';
        $this->filesystem->writeStream(
            'images/hotel/' . $fileName,
            $stream
        );
        fclose($stream);
        return 'images/hotel/' . $fileName;
    }

    public function delete($file_name)
    {
        $r = $this->filesystem->has($file_name);
        if (!$r){
            throw new BusinessException(10000, '图片不存在');
        }
        return $this->filesystem->delete($file_name);
    }
}