<?php

namespace App\Http\Controllers;

use App\Constants\Categires;
use App\Http\Services\CategoryService;
use App\Http\Services\CityService;
use App\Http\Services\CouponService;
use App\Http\Services\JavaService;
use App\Http\Services\StoreService;
use App\Models\TbCategory;
use App\Models\TbRegion;
use App\Models\TbSrcBrand;
use App\Models\TbSrcRegion;
use App\Models\TbSrcStore;
use App\Models\TbStore;
use Illuminate\Http\Request;
use function React\Promise\all;
use Illuminate\Support\Facades\Redis;

class StoreController extends Controller
{
    public function search(CityService $cityService, StoreService $storeService)
    {
        $requestData = request()->all(['city', 'longitude', 'latitude', 'search', 'tags', 'scope', 'limit', 'offset', 'banks', 'category_ids', 'batch']);

        $index = config('app.current_index');
        $tbCategory = new TbCategory();

        $city = $cityService->getCityByLatAndLon($requestData['latitude'], $requestData['longitude']);

//        if ($requestData['limit'] < 30) {
//            $requestData['limit'] = 30;
//            $requestData['offset'] = (intval($requestData['offset'] / $requestData['limit']) + 1) * 30;
//        }
        $requestData['limit'] = $requestData['limit'] * 1;
        $requestData['offset'] = $requestData['offset'] * 1;

# 废弃方案，暂时隐藏
//        if ($requestData['offset'] == 0) {
//            $page = 1;
//        } else {
//            $page = intval($requestData['offset'] / $requestData['limit']) + 1;
//        }
//        $javaStoreIds = $this->getJavaStoreIds([2], $requestData['longitude'], $requestData['latitude'], $requestData['scope'], $city->id, $page);

        // 如果是搜索
        if (!empty($requestData['search'])) {

            if (mb_strlen($requestData['search'], "utf-8") > 5) {
                $sort = [
                    [
                        "_score" => [
                            "order" => 'desc'
                        ],
                    ],
                    [
                        "_geo_distance" => [
                            "location" => [
                                "lat" => $requestData['latitude'],
                                "lon" => $requestData['longitude']
                            ],
                            "order" => "asc",
                            "unit" => 'm'
                        ]
                    ]
                ];
            } else {

                $sort = [
                    [
                        "_geo_distance" => [
                            "location" => [
                                "lat" => $requestData['latitude'],
                                "lon" => $requestData['longitude']
                            ],
                            "order" => "asc",
                            "unit" => 'm'
                        ]
                    ],
                    [
                        "_score" => [
                            "order" => 'desc'
                        ],
                    ]
                ];
            }

            $body = [
                "from" => $requestData['offset'],
                "size" => $requestData['limit'],
                "query" => [
                    "bool" => [
                        'should' => [
                            [
                                'bool' => [
                                    'must' => [
                                        [
                                            'match' => [
                                                'name' => [
                                                    "query" => $requestData['search'],
                                                    "minimum_should_match" => "75%"
                                                ]
                                            ]
                                        ],
                                        [
                                            'term' => [
                                                'city_id' => $city['id']
                                            ]
                                        ],
                                        [
                                            "geo_distance" => [
//                                    "distance_type" => "sloppy_arc",
                                                "distance" => $requestData['scope'],
                                                "location" => [
                                                    "lat" => $requestData['latitude'],
                                                    "lon" => $requestData['longitude']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                        ],
                        "minimum_should_match" => 1
                    ]
                ],
                "sort" => $sort
            ];

            $data = app('es')->searchDoc($index, '_doc', $body);
            $data = $data['hits']['hits'];
        } else {
            $esData = $storeService->getEsStoresByLongitudeAnLatitude($index, $requestData);
//            $javaData = $storeService->getEsStoresByJavaStoreIds($index, $javaStoreIds, $requestData);
            $javaData = [];
            $data = array_merge($esData, $javaData);
        }

        $response = [];
        $storeIds = [];

        foreach ($data as $datum) {
            $item = $datum['_source'];
            if (!empty($requestData['search'])) {
                if (mb_strlen($requestData['search'], "utf-8") > 5) {
                    $sort = $datum['sort'][1];
                } else {
                    $sort = $datum['sort'][0];
                }
            } else {
                $sort = $datum['sort'][0];
            }

            if ($sort > 1000) {
                $distance = number_format(($sort / 1000), 2) . 'km';
            } else {
                $distance = (int)$sort . 'm';
            }

            $item["rating"] = floatval(($item["rating"] ? number_format($item["rating"], 1) : ""));
            $item["average_cost"] = (int)$item["average_cost"] ? number_format($item["average_cost"], 0) : "";
            $item['categories'] = $item['category_name'] . " " . $item['category_name_2'] . " " . $item['category_name_3'];
//            $item['distance'] = $this->getDistance($requestData['longitude'], $requestData['latitude'], $item['longitude'], $item['latitude']);
            $item['distance'] = $distance;
            $item['logo'] = str_ireplace("http:", 'https:', $item['logo']);

            if ($item['coupon']) {
                $item['coupon'] = json_decode($item['coupon'], true);

                if (empty($item['logo'])) {
                    $item['logo'] = $item['coupon']['coupon_pic'];
                }
            }
            if ($item['group_purchase']) {
                $item['group_purchase'] = json_decode($item['group_purchase'], true);

                if (empty($item['logo'])) {
                    $item['logo'] = $item['group_purchase']['coupon_pic'];
                }
            }

            $redisCategory = Redis::get('category:' . $item['category_id_2']);
            if (!empty($redisCategory)) {
                $category = json_decode($redisCategory);
            } else {
                $category = $tbCategory->where('id', $item['category_id_2'])->first();
                Redis::set('category:' . $item['category_id_2'], json_encode($category, JSON_UNESCAPED_UNICODE));
            }

            $item['category_name_2'] = $category->name ?? '';

//            if(empty($item['coupon']) && empty($item['group_purchase'])) {
//
//            } else {
            $response[] = $item;
            $storeIds[] = $item['id'];
//            }
        }

        $response = $this->filterStoreIsCouponByJava($storeIds, $requestData['latitude'], $requestData['longitude'], $response);

//        $response = $this->buildJavaStore($response, [2], $requestData['longitude'], $requestData['latitude'], $requestData['scope'], $city->id);

        return [
            'code' => 1000,
            'data' => $response
        ];
    }

    public function getJavaStoreIds($shopIds, $lng, $lat, $radii, $cityId, $page)
    {
        $javaService = new JavaService();

        $storeIds = $javaService->getStores($shopIds, $lng, $lat, $radii, $cityId, $page);

        return $storeIds;
    }

    public function filterStoreIsCouponByJava($storeIds, $lat, $lng, $data)
    {
        $javaService = new JavaService();

        $storeIds = $javaService->meiTuanIsCoupon($storeIds, $lat, $lng);

        $filterData = [];

        foreach ($data as $key => $datum) {
            if ($datum['is_coupon'] == 1 || in_array($datum['id'], $storeIds)) {
                $filterData[] = $datum;
            }
        }

        return $filterData;
    }

    public function getCoupons($storeId, $date, $shopIds, $contentType)
    {
        $couponService = new CouponService();
        $data = $couponService->getCoupons($storeId, $shopIds, $date, $contentType);
        $days = $this->getDays();

        $coupon = [];
        $banks = ["任意银行"];
        $couponEffectiveDays = [];
        foreach ($data as $group) {
            foreach ($group as $item) {

                if ($item['bank']) {
                    $banks[] = $item['bank'];
                }

                if (empty($coupon) && isset($item['discount']) && $item['coupon_type'] != 3) {
                    $coupon = $item;
                    continue;
                }

                if (isset($item['discount']) && isset($coupon['discount']) && $item['discount'] < $coupon['discount'] && $item['coupon_type'] != 3) {
                    $coupon = $item;
                }

                foreach ($days as $day) {
                    if ($item['coupon_starttime'] <= $day && $item['coupon_endtime'] >= $day) {
                        $couponEffectiveDays[] = $day;
                    }
                }
            }
        }
        return [
            'coupon' => $coupon,
            'banks' => array_unique($banks),
            'coupon_effective_days' => array_unique($couponEffectiveDays),
        ];
    }

    public function getDays()
    {
        $dateArray = [];
        for ($i = 1; $i < 8; $i++) {
            $dateArray[$i] = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $i . 'day'));

        };
        return $dateArray;
    }

    public function getGroupPurchases($storeId, $shopIds = [2, 3])
    {
        $date = date('Y-m-d');
        $shopIdsStr = implode(',', $shopIds);
        $sql = "SELECT ss.shopid,sr.src_id 'srcCityNo',ss.id 'srcStoreId',ss.src_id 'srcStoreNo', tb_shop.name as shop_name,sc.*, (sc.sales_price / sc.original_price) as discount FROM tb_store s INNER JOIN tb_store_mapping sm ON s.id = sm.store_id INNER JOIN tb_src_store ss ON ss.id = sm.src_store_id AND ss.shopid in ({$shopIdsStr}) INNER JOIN tb_src_region sr ON ss.src_city_id = sr.id LEFT JOIN tb_src_store_coupon ssc ON ss.id = ssc.src_store_id  LEFT JOIN tb_src_coupon sc ON ssc.`shopid` = sc.shopid AND ssc.coupon_no = sc.coupon_no left join tb_shop  on tb_shop.id = ss.shopid  where s.id={$storeId} and (`coupon_starttime` < '{$date}' and `coupon_endtime` > '{$date}' or coupon_starttime is null ) and sc.coupon_type = 3 order by discount asc limit 1 ";

        $data = \DB::connection('elite_pay_data')->select($sql);

        if ($data) {
            $data = $data[0];
            $data->discount = number_format($data->discount, 2);
        }

        return $data;
    }

    public function show($id, StoreService $storeService)
    {
        $index = config('app.current_index', 'store_3');
//        $data = app('es')->getDoc($id, $index, '_doc');
        $requestData = request()->all(['latitude', 'longitude']);

        $store = $storeService->getStoreById($id, $index, $requestData['latitude'], $requestData['longitude']);

        $data = $store['_source'];

        if (empty($requestData['longitude'])) {
            $data['distance'] = '';
        } else {
            $sort = $store['sort'][0];
            if ($sort > 1000) {
                $distance = number_format(($sort / 1000), 2) . 'km';
            } else {
                $distance = (int)$sort . 'm';
            }
            $data['distance'] = $distance;
        }

        $tbCategory = new TbCategory();
        $category = $tbCategory->where('id', $data['category_id_2'])->first();
        $data['category_name_2'] = $category->name;

        $data["rating"] = floatval(($data["rating"] ? number_format($data["rating"], 1) : ""));
        $data["average_cost"] = (int)$data["average_cost"] ? number_format($data["average_cost"], 0) : "";
        $data['categories'] = $data['category_name'] . " " . $data['category_name_2'] . " " . $data['category_name_3'];

        return [
            'code' => 1000,
            'data' => $data
        ];
    }

    public function indexSyncSrcStore(TbSrcStore $tbSrcStore)
    {
        $lastId = (int)file_get_contents('./b.txt');
        $stores = $tbSrcStore->where('id', '>', $lastId)->limit(300)->orderBy('id', 'asc')->get()->toArray();
        $index = 'src_store_1';
        $last = [];

        foreach ($stores as $store) {
            $res = app('es')->addDoc($store['id'], $store, $index, '_doc');
            $last = $store;
        }

        file_put_contents('./b.txt', $last['id']);
    }

    public function indexSync(TbStore $tbStore)
    {
        $lastId = (int)file_get_contents('./a.txt');

        $index = 'store_9';

        $stores = $tbStore->with(['tbBrand', 'tbCategory', 'tbCategory', 'tbCategory2', 'tbCategory3', 'city', 'district', 'tbCommericalLocation'])
            ->where('id', '>', $lastId)->limit(300)->orderBy('id', 'asc')->get();

//        $stores = $tbStore->with(['tbBrand', 'tbCategory', 'tbCategory', 'tbCategory2', 'tbCategory3', 'city', 'district', 'tbCommericalLocation'])
//            ->where('id', '=', 325190)->limit(300)->orderBy('id', 'asc')->get();

        if ($stores->count() > 0) {

            $last = $stores->last();
            file_put_contents('./a.txt', $last->id);

            foreach ($stores as $store) {
                $esStore = app('es')->getDoc($store->id, $index, '_doc');

                $logo = '';
                if (!empty($esStore['_source'])) {
                    $logo = $esStore['_source']['logo'];
                }

                $data = [
                    "id" => $store->id,
                    "name" => $store->name,
                    "branch_name" => $store->branch_name,
                    "src_id" => $store->src_id,
                    "from_shop" => $store->from_shop,
                    "brand_id" => $store->brand_id,
                    "brand_name" => $store->tbBrand->name ?? '',
                    "category_id" => $store->category_id,
                    "category_name" => $store->tbCategory->name ?? '',
                    "category_id_2" => $store->category_id_2,
                    "category_name_2" => $store->tbCategory2->name ?? '',
                    "category_id_3" => $store->category_id_3,
                    "category_name_3" => $store->tbCategory3->name ?? '',
                    "city_id" => $store->city_id,
                    "city_name" => $store->city->name ?? '',
                    "district_id" => $store->district_id,
                    "district_name" => $store->district->name ?? '',
                    "commerical_location_id" => $store->commerical_location_id,
                    "commerical_location_name" => $store->tbCommericalLocation->name ?? '',
                    "logo" => $logo,
                    "rating" => $store->rating ? number_format($store->rating, 1) : "",
                    "average_cost" => $store->average_cost,
                    "address" => $store->address,
                    "tel" => $store->tel,
                    "hours" => $store->hours,
                    "special_info" => $store->special_info,
                    "longitude" => $store->longitude,
                    "latitude" => $store->latitude,
                    "status" => $store->status,
                    "remarks" => $store->remarks,
                    "is_delete" => $store->is_delete,
                    "createtime" => $store->createtime,
                    "updatetime" => $store->updatetime,
                    "shop_honor" => $store->shop_honor,
                    "location" => [
                        "lon" => $store->longitude,
                        "lat" => $store->latitude,
                    ]
                ];

                $tbStoreModel = new TbStore();
                $store = $tbStoreModel->with(['tbSrcStoreMapping.meiTuanSrcStore.city'])->where('id', $store->id)->first();


                $date = date('Y-m-d');
                $shopIds = [0, 2, 3];
                $contentType = [0, 1, 2];

                $couponData = $this->getCoupons($store->id, $date, $shopIds, $contentType);

                $coupon = $couponData['coupon'];
                $banks = $couponData['banks'];
                $couponEffectiveDays = $couponData['coupon_effective_days'];

                $groupPurchase = $this->getGroupPurchases($store->id, $shopIds);

                $data['is_coupon'] = 0;
                $data['coupon'] = '';
                $data['banks'] = $banks;
                $data['coupon_effective_days'] = $couponEffectiveDays;
                $data['group_purchase'] = '';
                $data['mei_tuan_store_mapping'] = json_encode($store->tbSrcStoreMapping, JSON_UNESCAPED_UNICODE);

                if ($groupPurchase) {
                    $data['group_purchase'] = json_encode($groupPurchase, JSON_UNESCAPED_UNICODE);

                    $data['is_coupon'] = 1;

                    if (empty($data['logo'])) {
                        $data['logo'] = $groupPurchase->coupon_pic ?? '';
                    }
                }

                if ($coupon) {
                    $data['coupon'] = json_encode($coupon, JSON_UNESCAPED_UNICODE);
                    $data['is_coupon'] = 1;

                    if (empty($data['logo'])) {
                        $data['logo'] = $coupon['coupon_pic'] ?? '';
                    }
                }

                $res = app('es')->addDoc($store->id, $data, $index, '_doc');
            }
        } else {
            file_put_contents('./a.txt', 0);
        }

        return [];
    }

    public function countStoreCityCategory(CategoryService $categoryService)
    {
        $res = $categoryService->storeCountByCityAndCategory();

        return $res;
    }

    public function getCityByShop(TbSrcRegion $tbSrcRegion, StoreService $storeService, TbSrcBrand $tbSrcBrand)
    {
        $requestData = request()->all(['shop_id', 'brand_id']);
        $brandIds = explode(',', $requestData['brand_id']);
        $index = 'src_store_1';

        $regions = $tbSrcRegion->with('city')->where('shopid', $requestData['shop_id'])->where('level', 2)->limit(15)->get();


        $brands = $tbSrcBrand->whereIn('id', $brandIds)->get();

        $res = [];
        foreach ($brands as $brand) {
            foreach ($regions as $key => $region) {
                $regionIsStore = false;
                foreach ($region['city'] as $k => $city) {
                    $stores = $storeService->getSrcStoreByCity($index, $city['id'], $brand->id);
                    if ($stores) {
                        $regions[$key]['city'][$k]['stores'] = $stores;
                        $regionIsStore = true;
                    } else {
                        unset($regions[$key]['city'][$k]);
                    }
                }

                if (!$regionIsStore) {
                    unset($regions[$key]);
                }
            }
            $res[] = [
                'id' => 'brand_' . $brand->id,
                'name' => $brand->name,
                'no_box' => false,
                'level' => 1
            ];

            foreach ($regions as $region) {
                $regionId = $region->id;
                $res[] = [
                    'id' => $regionId,
                    'name' => $region->name,
                    'parent_id' => 'brand_' . $brand->id,
                    'no_box' => false,
                    'level' => 2
                ];

                foreach ($region['city'] as $city) {
                    $res[] = [
                        'id' => $city->id,
                        'parent_id' => $regionId,
                        'name' => $city->name,
                        'no_box' => false,
                        'level' => 3
                    ];

                    foreach ($city['stores'] as $store) {
                        $res[] = [
                            'id' => $store['id'],
                            'parent_id' => $city->id,
                            'name' => $store['name'],
                            'no_box' => false,
                            'level' => 4
                        ];
                    }
                }
            }
        }

        return $res;
    }

    public function getSrcStoreBySrcCity(StoreService $storeService)
    {
        $requestData = request()->all(['shop_id', 'brand_id', 'city_id']);
        $index = 'src_store_1';

        $stores = $storeService->getSrcStoreByCity($index, $requestData['city_id'], $requestData['brand_id']);

        return $stores;
    }
}
