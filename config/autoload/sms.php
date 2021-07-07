<?php
/**
 * User：caogang
 * Email：291846152@qq.com
 * DateTime：2021/6/25 9:13
 */

return [
    'username'                  => env('SMS_USERNAME', 'blwlkj'),
    'password'                  => env('SMS_PASSWORD', 'blwlkj855'),
    'appid'                     => env('SMS_APPID', '300885'),

    // 订购机票支付成功的短信模板
    'pay_sms_templete'          => "尊敬的【username】旅客(证件号码:【idcardnum】):您预定的【startdate】/startcity--endcity/strttime起飞--endtime到达/flightno航班，已预定成功。请再次核对以上信息，尽快确认行程。如有疑问，及时致电02368852222。",
    // 出票短信模板
    'issue_ticket_sms_templete' => "尊敬的【username】旅客(证件号码:【idcardnum】):您预定的【startdate】/startcity--endcity/strttime起飞--endtime到达/flightno航班，已成功出票。请凭有效证件提前2小时到机场相应航空公司柜台办理登机牌，祝您旅途愉快！"
];
