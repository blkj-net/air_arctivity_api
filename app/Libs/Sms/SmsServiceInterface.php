<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/25 9:05
 */

namespace App\Libs\Sms;


interface SmsServiceInterface
{
    /**
     * 发送短信
     * User：caogang
     * DateTime：2021/6/25 9:08
     * @param $tel
     * @param $message
     * @return mixed
     */
    public function send($tel, $message);
}