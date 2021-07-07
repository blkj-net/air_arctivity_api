<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Common;
use App\Service\SystemConfigService;
use App\Service\SystemLogRecordService;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SystemLogMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var SystemLogRecordService
     */
    protected $systemLogRecordService;

    /**
     * @Inject
     * @var SystemConfigService
     */
    protected $configService;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected $exception = [
        'manage/log/list'
    ];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if (!in_array(request()->path(), $this->exception)) {
            $responseBody = $response->getBody()->getContents();
            $level = $this->configService->getValueByKey('system_log_level');
            $recordData = [
                'path'          => request()->path(),  // 请求路径
                'request_body'  => json_encode(request()->all(), JSON_UNESCAPED_UNICODE), // 请求参数
                'method'        => request()->getMethod(), // 请求方式
                'agent'         => request()->getHeader('user-agent')[0], // 浏览器代理
                'ip'            => request()->getServerParams()['remote_addr'], // 请求IP
                'status'        => $response->getStatusCode(), // 请求状态
                'response_body' => $responseBody, // 响应参数
                'level'         => $level // 日志等级
            ];
            if ($recordData['path'] == 'login') {
                $recordData['user_id'] = isset(json_decode($responseBody, true)['data']['id']) ? json_decode($responseBody, true)['data']['id'] : "";
            } else {
                $recordData['user_id'] = request()->userInfo['id'];
            }
            $responseBodyArray = json_decode($responseBody, true);
            if ($responseBodyArray['code']) {
                $recordData['error_message'] = $responseBody;
            } else {
                $recordData['error_message'] = "";
            }
            if (isset(Common::SYSTEM_LOG_ALLOW_METHOD[$level]) && in_array($recordData['method'], Common::SYSTEM_LOG_ALLOW_METHOD[$level])) {
                $this->systemLogRecordService->create($recordData);
            }
        }
        return $response;
    }
}