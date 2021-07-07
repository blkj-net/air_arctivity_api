<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/12 15:46
 */

namespace App\Exception\Handler;

use App\Exception\BusinessException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class BusinessExceptionHandler extends ExceptionHandler
{

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 格式化输出
        $data = json_encode([
            'code' => $throwable->getCode(),
            'msg'  => $throwable->getMessage()
        ], JSON_UNESCAPED_UNICODE);
        // 阻止异常冒泡
        $this->stopPropagation();
        return $response->withStatus(200)->withBody(new SwooleStream($data));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof BusinessException;
    }
}