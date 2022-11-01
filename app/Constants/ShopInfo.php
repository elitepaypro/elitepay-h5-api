<?php

namespace App\Constants;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class ShopInfo
{
    const MeiTuan = 'mei_tuan';
    const KouBei = 'kou_bei';
    const MinSheng = 'min_sheng';

    const Mapping = [
        self::MeiTuan => [
            'logo' => 'https://elitepay.obs.cn-east-3.myhuaweicloud.com/images/app/mt.png',
            'name' => '美团'
        ],
        self::MinSheng => [
            'logo' => 'https://elitepay.obs.cn-east-3.myhuaweicloud.com/images/app/qmsh.png',
            'name' => '民生全民生活'
        ],
        self::KouBei => [
            'logo' => 'https://elitepay.obs.cn-east-3.myhuaweicloud.com/images/app/alipay.png',
            'name' => '支付宝',
//            'ios_scheme' => 'alipays://platformapi/startapp?appId=20000056',
//            'android_scheme' => 'alipays://platformapi/startapp?appId=20000056',
//            'android_package' => 'com.eg.android.AlipayGphone',
//            'coupon_url' => 'alipays://platformapi/startapp?appId=20000056',
//            'url' => 'https://elitepay.cn/app-download/?id=3'
        ],
    ];
}
