<?php

namespace App\Http\Controllers;

use App\Constants\ShopInfo;
use App\Models\TbSrcStoreCoupon;
use Illuminate\Http\Request;
use App\Http\Services\CouponService;
use Illuminate\Support\Facades\Redis;

class CouponController extends Controller
{
    public function index()
    {

        $storeId = request('store_id');
        $shopIdsStr = request('shop_ids');
        $date = request('date', date('Y-m-d'));
        $tenant = request('tenant');
        $couponService = new CouponService();

        if (is_string($shopIdsStr)) {
            $shopIds = explode(',', $shopIdsStr);
            $shopIds[] = 0;
            $shopIds[] = 30;
            $shopIds[] = 4;
        } else {
            $shopIds = $shopIdsStr;
        }
        if ($tenant == 'tonglian') {
            $shopIds = [2];
        }

        $redisKey = "coupon:{$date}:{$storeId}:$shopIdsStr:4";
        $cache = Redis::get($redisKey);

        if (empty($cache)) {
            $days = $this->getDays();
            $couponTypes = [0, 1, 2];
            $couponData = $couponService->getCouponByDays($days, $storeId, $shopIds, $couponTypes);
            Redis::setex($redisKey, 86400, json_encode($couponData, JSON_UNESCAPED_UNICODE));
        } else {
            $couponData = json_decode($cache, true);
        }

        return [
            'code' => 1000,
            'data' => $couponData
        ];
    }

    public function getDays()
    {
        $dateArray = [];

        for ($i = 0; $i < 7; $i++) {
            $dateArray[$i] = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $i . 'day'));
        };

        return $dateArray;
    }

    public function groupPurchase(CouponService $couponService)
    {
        $storeId = request('store_id');
        $shopIds = request('shop_ids');
        $date = request('date', date('Y-m-d'));
        $tenant = request('tenant');

        if (is_string($shopIds)) {
            $shopIds = explode(',', $shopIds);
            $shopIds[] = 0;
        }
        if ($tenant == 'tonglian') {
            $shopIds = [2];
        }

        $data = $couponService->getCoupons($storeId, $shopIds, $date, [3]);
        $data['has_coupon'] = true;
        unset($data["pay_code"], $data["pay_for_preferential"], $data["vouchers"]);
//        $data = $data['group_purchase'];

        if(empty($data['group_purchase'])) {
            $data['group_purchase'] = [];
        } else {
            $groupPurchase = $data['group_purchase'];
            sort($groupPurchase);
            $data['group_purchase'] = $groupPurchase;
        }
        return [
            'code' => 1000,
            'data' => $data
        ];
    }

    public function proxyCoupon(CouponService $couponService)
    {
        $data = request()->all(['proxy', 'shop_id', 'store_id']);

        $data = $couponService->getJavaCoupon($data);

        return [
            'code' => 1000,
            'data' => $data
        ];
    }

    public function store(CouponService $couponService, TbSrcStoreCoupon $srcStoreCoupon)
    {
        $data = request()->all(['coupon', 'store_coupons']);

        // 添加优惠券
        $coupon = $couponService->insert($data['coupon']);

        // 添加关联关系
        $res = $srcStoreCoupon->insert($data['store_coupons']);

        return [
            'code' => 1000,
            'data' => [
                $res,
                $coupon
            ]
        ];
    }
}

