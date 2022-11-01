<?php

namespace App\Http\Services;


use App\Models\TbSrcStore;
use App\Models\TbStore;
use App\Models\TbStoreMapping;
use Illuminate\Support\Facades\Redis;

class JavaService
{
    public function getStores($shopIds, $lng, $lat, $radii, $cityId, $page, $limit = 10)
    {

        $client = new \GuzzleHttp\Client();
        $cityService = new CityService();
        $storeService = new StoreService();
        $javaUrl = config('app.java_api_url');

        $stores = [];
        foreach ($shopIds as $shopId) {
            ## 获取实时店铺
            $srcRegion = $cityService->getSrcRegion($cityId, $shopId);
            $url = $javaUrl . "/coupon/getStoreAndCoupon?shopId={$shopId}&lng={$lng}&lat={$lat}&radii={$radii}&srcCityId={$srcRegion['id']}&srcCityNo={$srcRegion['src_id']}&pid=247050&limit={$limit}&now={$page}";

            $redisKey = 'java:store:' . md5($url);

            $cache = Redis::get($redisKey);

            if (empty($cache)) {

                $response = $client->get($url);
                $response = json_decode($response->getBody()->getContents(), true);

                $stores = array_merge($stores, $response['data']['data']);

                $stores = $this->assembleJavaStoreData($stores);

                Redis::setex($redisKey, 86400, json_encode($stores, JSON_UNESCAPED_UNICODE));
            } else {
                $stores = json_decode($cache, true);
            }
        }

        $srcStoreIds = [];
        foreach ($stores as $key => $store) {
            if (!empty($store['group_purchase']) || !empty($store['src_coupon'])) {
                $srcStoreIds[] = $store['id'];
            }
        }

        $storeMapping = $storeService->getStoreBySrcStoreIds($srcStoreIds)->pluck('store_id')->toArray();

        return $storeMapping;
    }

    //数组中键为驼峰的转下划线
    public function convertHumpToLine(array $data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item) || is_object($item)) {
                $result[$this->humpToLine($key)] = $this->convertHumpToLine((array)$item);
            } else {
                $result[$this->humpToLine($key)] = trim($item);
            }
        }
        return $result;
    }

    public function humpToLine($str)
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }

    public function assembleJavaStoreData($data)
    {
        $data = $this->convertHumpToLine($data);

        foreach ($data as $key => $datum) {
            $data[$key]['is_coupon'] = 0;
            $data[$key]['coupon'] = '';
            $data[$key]['group_purchase'] = '';

            if (!empty($datum['src_coupon'])) {
                if ($datum['src_coupon']['coupon_type'] == 3) {
                    $data[$key]['group_purchase'] = $datum['src_coupon'];

                    $data[$key]['is_coupon'] = 1;
                } else {
                    $data[$key]['coupon'] = $datum['src_coupon'];

                    $data[$key]['is_coupon'] = 1;

                }
            }
        }

        return $data;
    }

    public function meiTuanIsCoupon($storeIds, $lat, $lng)
    {
        $client = new \GuzzleHttp\Client();

        $tbStoreModel = new TbStore();

        $javaUrl = config('app.java_api_url');

        $stores = $tbStoreModel->with(['tbSrcStoreMapping.meiTuanSrcStore.city'])->whereIn('id', $storeIds)->get();

        $srcStoreIds = [];
        $srcCityId = $srcCityNo = 0;
        $srcStoreNoMapping = [];
        foreach ($stores as $key => $store) {
            if (isset($store->tbSrcStoreMapping)) {
                foreach ($store->tbSrcStoreMapping as $tbSrcStoreMapping) {
                    if (isset($tbSrcStoreMapping->meiTuanSrcStore)) {
                        $srcStoreIds[] = $tbSrcStoreMapping->meiTuanSrcStore->src_id;

                        $srcStoreNoMapping[$tbSrcStoreMapping->meiTuanSrcStore->src_id] = $store->id;

                        if (isset($tbSrcStoreMapping->meiTuanSrcStore->city)) {
                            $srcCityId = $tbSrcStoreMapping->meiTuanSrcStore->src_city_id;
                            $srcCityNo = $tbSrcStoreMapping->meiTuanSrcStore->city->src_id;
                        }
                    }
                }
            }
        }

        $srcStoreNos = implode(',', $srcStoreIds);

        $url = $javaUrl . "/coupon/getHasCouponStores?srcCityId={$srcCityId}&srcCityNo={$srcCityNo}&srcStoreNos={$srcStoreNos}&lng={$lng}&lat={$lat}";
//        $url = $javaUrl . "/coupon/getHasCouponStores?srcCityId=23&srcCityNo=1&srcStoreNos=183979631,1183701358&lng=116.407400&lat=39.904200";

        $response = $client->get($url);
        $response = json_decode($response->getBody()->getContents(), true);

        $srcStoreNos = $response['data']['srcStoreNos'] ?? [];

        $storeIds = [];

        foreach ($srcStoreNos as $srcStoreNo) {
            $storeIds[] = $srcStoreNoMapping[$srcStoreNo];
        }

        return $storeIds;

    }

//    public function getCoupons($storeId)
//    {
//
//        $client = new \GuzzleHttp\Client();
//        $storeService = new StoreService();
//
//        $onlineStores = $storeService->getJavaSrcStores($storeId);
//
//        $javaUrl = config('app.java_api_url');
//        $coupons = [];
//        foreach ($onlineStores as $onlineStore) {
//            $url = $javaUrl . "/coupon/get?shopId={$data['shop_id']}&srcStoreId={$onlineStore->src_store_id}&srcStoreNo={$onlineStore->src_store_no}&srcCityNo={$onlineStore->src_city_no}&pid=268083";
//
//            $response = $client->get($url);
//            $couponData = json_decode($response->getBody()->getContents(), true);
//            $coupons = array_merge($coupons, $couponData['data']['data']);
//        }
//    }
}
