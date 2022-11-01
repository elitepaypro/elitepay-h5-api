<?php

namespace App\Http\Services;

use App\Constants\ShopInfo;
use App\Models\TbSrcCoupon;

class CouponService
{

    public function getCoupons($storeId, $shopIds, $date, $contentType)
    {
        // 获取新优惠券
        $data = $this->getNewCoupons($storeId, $shopIds, $date, $contentType);

        $data = $this->buildOldCoupon($storeId, $data, $date);

        // 过滤优惠券
        $data = $this->filterShopId($shopIds, $data);

        // 去重优惠券
        $data = $this->deWeightCoupons($data);

        return $data;
    }

    public function getCouponByDays($days, $storeId, $shopIds, $couponTypes)
    {
        $couponData = [];
        $i = 0;
        foreach ($days as $date) {
            $data = $this->getCoupons($storeId, $shopIds, $date, $couponTypes);

            $hasCoupon = false;
            $week = date('w', strtotime($date));
            $i++;

            foreach ($data as $k => $coupons) {

                foreach ($coupons as $index => $coupon) {
                    if (!empty($coupon['coupon_valid_week']) && ((string)$coupon['coupon_valid_week'])[$week - 1] != 1) {
                        unset($data[$k][$index]);
                        continue;
                    }
                }

                $nowCoupons = $data[$k];

                sort($nowCoupons);

                $data[$k] = $nowCoupons;
                if (!empty($data[$k]) && $k != 'group_purchase') {
                    $hasCoupon = true;
                }
            }

            $data['has_coupon'] = $hasCoupon;

            unset($data['group_purchase']);
            $couponData[] = $data;
        }

        return $couponData;
    }

    public function deWeightCoupons($coupons)
    {
        $deWeightData = [];

        foreach ($coupons as $key => $couponList) {
            foreach ($couponList as $k => $coupon) {
                if (isset($deWeightData[$coupon['coupon_title'] . $coupon['coupon_type'] . $coupon['original_price']])) {
                    unset($coupons[$key][$k]);
                } else {
                    $deWeightData[$coupon['coupon_title'] . $coupon['coupon_type'] . $coupon['original_price']] = 1;
                }

                // 去掉APP里美团的券
                if ($coupon['shop_id'] == 0 && $coupon['source']['name'] == '美团支付') {
                    unset($coupons[$key][$k]);
                }
            }
        }

        return $coupons;
    }

    public function filterShopId($shopIds, $data)
    {
        foreach ($data as $key => $datum) {
            foreach ($datum as $k => $item) {
                if (!in_array($item['shop_id'], $shopIds)) {
                    unset($data[$key][$k]);
                }
            }
        }

        return $data;
    }

    public function buildOldCoupon($storeId, $data, $date)
    {
        // 获取旧优惠券
        $oldCoupon = $this->getOldCoupons($storeId, $date);

        $data = array_merge($data, $oldCoupon);

        // 组装数据
        foreach ($data as $key => $datum) {

//            if ($week) {
//                if (!empty($datum['coupon_valid_week']) && ((string)$datum['coupon_valid_week'])[$week - 1] != 1) {
//                    unset($data[$key]);
//                    continue;
//                }
//            }
            if (is_numeric($datum['discount'])) {
                $data[$key]['discount'] = floor($datum['discount'] * 100) / 100;
            } else {
                $data[$key]['discount'] = 0;
            }
        }

        $data = $this->assemblyResponseData($data);

        if (!empty($data)) {
            $data = $this->couponGroupBy($data);

            foreach ($data as $key => $datum) {
                $data[$key] = $this->arraySort($datum, 'discount');
            }
        }

        return $data;
    }

    public function couponGroupBy($data)
    {
        $groupByData = [];

        foreach ($data as $datum) {
            if ($datum['coupon_type'] == 0) {
                $groupByData['pay_code'][] = $datum;
            }
            if ($datum['coupon_type'] == 1) {
                $groupByData['pay_for_preferential'][] = $datum;
            }
            if ($datum['coupon_type'] == 2) {
                $groupByData['vouchers'][] = $datum;
            }
            if ($datum['coupon_type'] == 3) {
                $groupByData['group_purchase'][] = $datum;
            }
        }

        return $groupByData;
    }

    public function arraySort($arr, $keys, $orderby = 'asc')
    {
        $keysvalue = $new_array = array();

        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($orderby == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[] = $arr[$k];
        }
        return $new_array;
    }

    public function assemblyResponseData($data)
    {
        $responseData = [];

        foreach ($data as $item) {
            $picUrl = $item['coupon_pic'] ?? '';
            if (!empty($item['coupon_pic'])) {
                $picUrl = str_ireplace("http:", 'https:', $picUrl);
            }

            if (isset($item['discount'])) {
                $discount = $item['discount'] > 0 ? $item['discount'] : '';
            } else if ($item['subsidie_price'] > 0 && $item['sales_price'] > 0) {
                $discount = number_format($item['subsidie_price'] / $item['original_price'], 2);
            } else if ($item['original_price'] > 0 && $item['sales_price'] > 0) {
                $discount = number_format($item['sales_price'] / $item['original_price'], 2);
            } else {
                $discount = '';
            }

            $originalPrice = $item['original_price'] ?? '';

            // 折扣大于9则隐藏
            if ($discount >= 9) {
                $originalPrice = '';
            }

            $oldCouponAssemblyItem = [];
            $oldCouponAssemblyItem['id'] = $item['id'] ?? ''; // id
            $oldCouponAssemblyItem['shop_id'] = $item['shopid'] ?? 0; // 渠道id
            $oldCouponAssemblyItem['coupon_launchins'] = $item['coupon_launchins'] ?? ''; // 副标题
            $oldCouponAssemblyItem['coupon_title'] = $item['coupon_title'] ?? ''; // 标题
            $oldCouponAssemblyItem['coupon_info'] = $item['coupon_info'] ?? ''; // 优惠券详情
            $oldCouponAssemblyItem['coupon_pic'] = $picUrl; // logo
            $oldCouponAssemblyItem['coupon_click_url'] = $item['coupon_click_url'] ?? ''; // 点击链接
            $oldCouponAssemblyItem['discount'] = $discount; // 折扣
            $oldCouponAssemblyItem['coupon_no'] = $item['coupon_no'] ?? ''; // 券码
            $oldCouponAssemblyItem['coupon_type'] = $item['coupon_type'] ?? ''; // 优惠券类型
            $oldCouponAssemblyItem['source'] = $item['source'] ?? []; // 银行卡来源
            $oldCouponAssemblyItem['bank'] = $item['bank'] ?? ''; // 银行
            $oldCouponAssemblyItem['card_name'] = $item['card_name'] ?? ''; // 银行卡名
            $oldCouponAssemblyItem['sales_price'] = $item['sales_price'] ?? ''; // 实际售价
            $oldCouponAssemblyItem['month_sale'] = $item['month_sale'] ?? ''; // 销量
            $oldCouponAssemblyItem['original_price'] = $originalPrice; // 原始价格
            $oldCouponAssemblyItem['coupon_endtime'] = $item['coupon_endtime'] ?? ''; // 有效开始时间
            $oldCouponAssemblyItem['coupon_starttime'] = $item['coupon_starttime'] ?? ''; // 有效结束时间
            $oldCouponAssemblyItem['coupon_valid_week'] = $item['coupon_valid_week'] ?? ''; // 生效周几
            $oldCouponAssemblyItem['subsidie_price'] = $item['subsidie_price'] ?? ''; // 券后价
            $oldCouponAssemblyItem['ninety_sale'] = $item['ninety_sale'] ?? ''; // 90天销量
            $oldCouponAssemblyItem['all_sale'] = $item['all_sale'] ?? ''; // 总销量
            $oldCouponAssemblyItem['sales_credits'] = $item['sales_credits'] ?? ''; // 积分

            $responseData[] = $oldCouponAssemblyItem;
        }

        return $responseData;
    }

    public function getNewCoupons($storeId, $shopIds, $date, $couponType)
    {
        $couponTypeStr = implode(',', $couponType);
        $shopIdsStr = implode(',', $shopIds);
        $sql = "SELECT ss.shopid,sr.src_id 'srcCityNo',ss.id 'srcStoreId',ss.src_id 'srcStoreNo', tb_shop.name as shop_name,sc.*, (sc.sales_price / sc.original_price) as discount  FROM tb_store s INNER JOIN tb_store_mapping sm ON s.id = sm.store_id INNER JOIN tb_src_store ss ON ss.id = sm.src_store_id AND ss.shopid in ({$shopIdsStr}) INNER JOIN tb_src_region sr ON ss.src_city_id = sr.id LEFT JOIN tb_src_store_coupon ssc ON ss.id = ssc.src_store_id  LEFT JOIN tb_src_coupon sc ON ssc.`shopid` = sc.shopid AND ssc.coupon_no = sc.coupon_no and  s.id={$storeId}  left join tb_shop  on tb_shop.id = ss.shopid where (`coupon_starttime` < '{$date}' and `coupon_endtime` > '{$date}' or coupon_starttime is null ) and sc.status=1 and sc.coupon_type in ({$couponTypeStr}) limit 100";

//        $sql = "SELECT *, (sc.sales_price / sc.original_price) as discount FROM tb_src_store_coupon ssc  INNER JOIN `tb_src_coupon` sc ON ssc.coupon_no = sc.coupon_no AND ssc.shopid = sc.shopid WHERE ssc.src_store_id IN ({$storeId}) and sc.shopid in ({$shopIds})  and ((`coupon_starttime` < '{$date}' and `coupon_endtime` > '{$date}') or `coupon_starttime` is null) and sc.coupon_type in ({$couponTypeStr})";

        $data = \DB::connection('elite_pay_data')->select($sql);
        if (!empty($data)) {
            $data = $this->objectToArray($data);
        }

        return $this->assemblyShopData($data);
    }

    public function assemblyShopData($data)
    {
        $ids = [];
        foreach ($data as $key => $datum) {
            if (!empty($datum['id']) && in_array($datum['id'], $ids)) {
                unset($data[$key]);
                continue;
            } else {
                $ids[] = $datum['id'];
            }

            $shopId = $datum['shopid'] ?? $datum['shop_id'];

            if ($shopId == 2) {
                $data[$key]['source'] = ShopInfo::Mapping[ShopInfo::MeiTuan];
            }

            if ($shopId == 3) {
                if ($datum['coupon_type'] == 2) {
                    if ((int)$datum['original_price'] < 1) {
                        $data[$key]['coupon_title'] = '代金券';
                    } else {
                        $data[$key]['coupon_title'] = (int)$datum['original_price'] . '元' . '代金券';
                    }
                }
                $data[$key]['coupon_click_url'] = $datum['alipay_scheme_url'];

//                $data[$key]['discount'] = '';
//
//                $data[$key]['original_price'] = '';

                $data[$key]['source'] = ShopInfo::Mapping[ShopInfo::KouBei];
            }

            if ($shopId == 30) {
                $data[$key]['source'] = ShopInfo::Mapping[ShopInfo::MinSheng];
                $data[$key]['coupon_click_url'] = [
                    'ios' => "MSCreditCard://action=openWeb&loginType=0&LOGIN_URL=thirdLoginCommon&lclURL=https%3A%2F%2Fshangwu.creditcard.cmbc.com.cn%2Fprefpay%2F%23%2FShopDetail%3FshopId%3D{$datum['srcStoreNo']}",
                    'android' => "ms://credit?action=openWeb&loginType=0&LOGIN_URL=thirdLoginCommon&lclURL=https%3A%2F%2Fshangwu.creditcard.cmbc.com.cn%2Fprefpay%2F%23%2FShopDetail%3FshopId%3D{$datum['srcStoreNo']}",
                ];
            }
        }

        return $data;
    }

    public function objectToArray($object)
    {
        //先编码成json字符串，再解码成数组
        return json_decode(json_encode($object), true);
    }

    public function assemblyCoupon($oldCoupon)
    {
        $oldCouponAssemblyData = [];
        foreach ($oldCoupon as $item) {
            $oldCouponAssemblyItem = [];
            $oldCouponAssemblyItem['id'] = $item['id']; // 副标题
            $oldCouponAssemblyItem['coupon_launchins'] = $item['card_name']; // 副标题
            $oldCouponAssemblyItem['coupon_title'] = $item['title']; // 标题
            $oldCouponAssemblyItem['coupon_info'] = $item['description']; // 优惠券详情
            $oldCouponAssemblyItem['coupon_pic'] = $item['cover_image']; // logo
            $oldCouponAssemblyItem['coupon_click_url'] = $item['url']; // 点击链接
            $oldCouponAssemblyItem['bank'] = $item['bank'] ?? ''; // 银行
            $oldCouponAssemblyItem['card_name'] = $item['card_name'] ?? ''; // 银行卡名
            $oldCouponAssemblyItem['sales_price'] = $item['sales_price'] ?? ''; // 价格
            $oldCouponAssemblyItem['sales_credits'] = $item['sales_credits'] ?? ''; // 积分
            $oldCouponAssemblyItem['month_sale'] = ''; // 销量

//            if (is_numeric($item['original_price']) && is_numeric($item['sales_price']) && $item['original_price'] > 0) {
//                $oldCouponAssemblyItem['discount'] = floor(($item['sales_price'] / $item['original_price']) * 100) / 100; // 折扣
//            } else {
            $oldCouponAssemblyItem['discount'] = $item['discount_rate']; // 折扣
//            }
            $oldCouponAssemblyItem['coupon_no'] = ''; // 券码
            $oldCouponAssemblyItem['coupon_type'] = $item['coupon_type']; // 优惠券类型
            $oldCouponAssemblyItem['source'] = $item['source']; // 银行卡来源

            $oldCouponAssemblyItem['original_price'] = $item['original_price'] ?? ''; // 原始价格
            $oldCouponAssemblyItem['coupon_starttime'] = $item['valid_time_from'] ?? ''; // 有效开始时间
            $oldCouponAssemblyItem['coupon_endtime'] = $item['valid_time_to'] ?? ''; // 有效结束时间
            $oldCouponAssemblyItem['coupon_valid_week'] = $item['valid_week_day'] ?? ''; // 生效日期

            $oldCouponAssemblyData[] = $oldCouponAssemblyItem;
        }

        return $oldCouponAssemblyData;
    }

    public function getOldCoupons($storeId, $date)
    {
        $storeService = new StoreService();
        $onlineStore = $storeService->getSrcStores($storeId);

        if (empty($onlineStore)) {
            return [];
        }
        $oldCoupons = [];
        $client = new \GuzzleHttp\Client();

        foreach ($onlineStore as $store) {
            $url = "http://app.elitepay.cn/store/coupons?store_id={$store->online_store_id}&date={$date}";

            $response = $client->get($url);
            $oldCouponData = json_decode($response->getBody()->getContents(), true);
            $oldCoupons = array_merge($oldCoupons, $oldCouponData['data']['coupons']);

//            if (!empty($oldCoupons)) {
//                break;
//            }

        }
        return $this->assemblyCoupon($oldCoupons);
    }

    public function getJavaCoupon($data)
    {
        $client = new \GuzzleHttp\Client();
        $storeService = new StoreService();

        $onlineStores = $storeService->getJavaSrcStores($data['store_id']);

        $javaUrl = config('app.java_api_url');
        $coupons = [];
        foreach ($onlineStores as $onlineStore) {
            $url = $javaUrl . "/coupon/get?shopId={$data['shop_id']}&srcStoreId={$onlineStore->src_store_id}&srcStoreNo={$onlineStore->src_store_no}&srcCityNo={$onlineStore->src_city_no}&pid=268083";

            $response = $client->get($url);
            $couponData = json_decode($response->getBody()->getContents(), true);
            if (!empty($couponData['data']['data'])) {
                $coupons = array_merge($coupons, $couponData['data']['data']);
                break;
            }
        }

        $data = $this->assemblyResponseData($coupons);

        $data = $this->assemblyShopData($data);

        if (!empty($data)) {
            $data = $this->couponGroupBy($data);

            foreach ($data as $key => $datum) {
                $data[$key] = $this->arraySort($datum, 'discount');
            }
        }

        return $data;
    }

    public function insert($data)
    {
        $tbSrcCoupon = new TbSrcCoupon();

        return $tbSrcCoupon->insert($data);
    }
}
