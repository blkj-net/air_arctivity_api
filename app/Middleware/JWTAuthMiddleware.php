<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Exception\BusinessException;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Phper666\JWTAuth\JWT;
use Phper666\JWTAuth\Util\JWTUtil;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Token中间件
 * Class JWTAuthMiddleware
 * @package App\Middleware
 */
class JWTAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * @var JWT
     */
    protected $jwt;

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(HttpResponse $response, JWT $jwt, RequestInterface $request)
    {
        $this->response = $response;
        $this->jwt = $jwt;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $isValidToken = false;
        // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
        $token = $request->getHeaderLine('Authorization') ?? '';
        if (strlen($token) > 0) {
            $token = JWTUtil::handleToken($token);
            if ($token !== false && $this->jwt->checkToken($token)) {
                $isValidToken = true;
            }
        }
        if ($isValidToken) {
            $userInfo = $this->jwt->getParserData($token);
            $userInfo['token'] = $token;
            $this->request->userInfo = $userInfo;
            return $handler->handle($request);
        }
        throw new BusinessException(401, 'Token authentication does not pass');
    }
}