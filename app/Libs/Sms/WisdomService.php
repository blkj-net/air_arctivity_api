<?php
/**
 * 智慧云短信服务
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/25 9:09
 */

namespace App\Libs\Sms;


use App\Exception\BusinessException;
use GuzzleHttp\Ring\Exception\CancelledException;
use Hyperf\Config\Annotation\Value;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class WisdomService implements SmsServiceInterface
{

    /**
     * 用户名
     * @Value("sms.username")
     */
    protected $username;
    /**
     * 密码
     * @Value("sms.password")
     */
    protected $password;
    /**
     * appid
     * @Value("sms.appid")
     */
    protected $epid;

    const URL = "http://q.hl95.com:8061/";

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        // 第一个参数对应日志的 name, 第二个参数对应 config/autoload/logger.php 内的 key
        $this->logger = $loggerFactory->get('log', 'sms');
    }

    /**
     * 发送短信
     * @inheritDoc
     */
    public function send($tel, $message)
    {
        if (!$message) {
            return ["code" => 10000, "msg" => "短信内容不能为空"];
        }
        // 验证手机号格式
        if (!preg_match('/^[1][3,4,5,7,8,9][0-9]{9}$/', $tel)) {
            return ["code" => 10000, "msg" => "手机号格式不正确"];
        }
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'epid'     => $this->epid,
            'phone'    => $tel,
            'message'  => $message,
        ];
        try {
            $client = new \GuzzleHttp\Client();
            $ret = $client->post(self::URL, ['form_params' => $params]);
            $result = $ret->getBody()->getContents();
            $res = $this->handdleResult($result);
            $this->logger->info(json_encode($res, JSON_UNESCAPED_UNICODE));
            return $res;
        } catch (\Exception $exception) {
            $this->logger->info($exception->getMessage());
            return ["code" => 10000, "msg" => $exception->getMessage()];
        }

    }

    /**
     * 返回值处理
     * User：caogang
     * DateTime：2021/6/25 10:46
     * @param $str
     * @return array
     */
    public function handdleResult($str)
    {
        $result = [
            "code" => 10000,
            "msg"  => "未知错误"
        ];
        switch ($str) {
            case "00":
                $result['code'] = 0;
                $result['msg'] = "提交成功";
                break;
            case "1":
                $result['msg'] = "参数不完整，请检查所带的参数名是否都正确";
                break;
            case "2":
                $result['msg'] = "鉴权失败，一般是用户名密码不对";
                break;
            case "3":
                $result['msg'] = "号码数量超出 300 条";
                break;
            case "4":
                $result['msg'] = "发送失败";
                break;
            case "5":
                $result['msg'] = "余额不足";
                break;
            case "6":
                $result['msg'] = "发送内容含屏蔽词";
                break;
            case "7":
                $result['msg'] = "短信内容超出 1000 个字";
                break;
            case "72":
                $result['msg'] = "内容被审核员屏蔽";
                break;
            case "9":
                $result['msg'] = "夜间管理，不允许一次提交超过 20 个号码";
                break;
        }
        if (strpos($str, "ERR IP:") !== false) {
            $result['msg'] = "IP 验证未通过，请联系管理员增加鉴权 IP";
        }
        return $result;
    }
}