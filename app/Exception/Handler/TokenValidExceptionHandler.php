<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/5/13 9:31
 */

namespace App\Exception\Handler;


use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Phper666\JWTAuth\Exception\JWTException;
use Phper666\JWTAuth\Exception\TokenValidException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class TokenValidExceptionHandler extends ExceptionHandler
{

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 格式化输出
        $data = json_encode([
            'code' => $throwable->getCode(),
            'msg'  => $throwable->getMessage(),
        ], JSON_UNESCAPED_UNICODE);
        // 阻止异常冒泡
        $this->stopPropagation();
        return $response->withStatus(200)->withBody(new SwooleStream($data));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof TokenValidException || $throwable instanceof JWTException;
    }
}