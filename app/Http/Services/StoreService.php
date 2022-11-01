<?php

namespace App\Http\Services;

use App\Constants\Categires;
use App\Models\TbStoreMapping;

class StoreService
{
    public function getSrcStores($storeId)
    {
        $getStoreIdSql = "SELECT osm.store_id online_store_id,sm.store_id base_store_id, ss.id as src_store_id, ss.src_id as src_store_no, sr.src_id as src_city_no FROM online_tb_store_mapping osm INNER JOIN tb_src_store ss ON osm.src_store_id = ss.id INNER JOIN `tb_store_mapping` sm ON ss.id = sm.src_store_id LEFT JOIN tb_src_region sr ON sr.id = ss.src_city_id AND sr.level = 3  where sm.store_id = {$storeId}  limit 10 ";

        $onlineStore = \DB::connection('elite_pay_data')->select($getStoreIdSql);

        return $onlineStore;
    }

    public function getJavaSrcStores($storeId)
    {
        $getStoreIdSql = "SELECT sm.store_id base_store_id, ss.id as src_store_id, ss.src_id as src_store_no, sr.src_id as src_city_no FROM tb_src_store ss INNER JOIN `tb_store_mapping` sm ON ss.id = sm.src_store_id LEFT JOIN tb_src_region sr ON sr.id = ss.src_city_id AND sr.level = 3  where sm.store_id = {$storeId}  limit 10";

        $onlineStore = \DB::connection('elite_pay_data')->select($getStoreIdSql);

        return $onlineStore;
    }

    public function getStoreBySrcStoreIds($srcStoreIds)
    {
        $tbStoreMappingModel = new TbStoreMapping();

        $storeMapping = $tbStoreMappingModel->whereIn('src_store_id', $srcStoreIds)->get();

        return $storeMapping;
    }

    public function getEsStoresByJavaStoreIds($index, $javaStoreIds, $requestData)
    {
        $body = [
            "query" => [
                "terms" => [
                    "id" => $javaStoreIds
                ]
            ],
            "sort" => [
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
            ]
        ];

        $data = app('es')->searchDoc($index, '_doc', $body);

        return $data['hits']['hits'] ?? [];
    }

    public function getEsStoresByLongitudeAnLatitude($index, $requestData)
    {
        $must = [
//            [
//                'term' => [
//                    'is_coupon' => 1
//                ]
//            ]
        ];

        if ($requestData['banks']) {
            $must[] = [
                'terms' => [
                    'banks' => $requestData['banks']
                ]
            ];
        }

        if ($requestData['category_ids']) {
            $categoryIds = $this->getCategories($requestData['category_ids'][0]);
            $must[] = [
                'terms' => [
                    'category_id_2' => $categoryIds
                ]
            ];
        }

        $body = [
            "from" => $requestData['offset'],
            "size" => $requestData['limit'],
            "query" => [
                "bool" => [
                    "filter" => [
                        "geo_distance" => [
//                                "distance_type" => "sloppy_arc",
                            "distance" => $requestData['scope'],
                            "location" => [
                                "lat" => $requestData['latitude'],
                                "lon" => $requestData['longitude']
                            ]
                        ]
                    ],
                    "should" => [
                        [
                            "bool" => [
                                "must" => $must
                            ]
                        ]
                    ],
                    "minimum_should_match" => 1
                ]
            ],
            "sort" => [
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
            ]
        ];

        $data = app('es')->searchDoc($index, '_doc', $body);

        return $data['hits']['hits'] ?? [];

    }

    public function getCategories($categoryId)
    {
        $categoryIds = [];
        if ($categoryId > 1000 && $categoryId < 1013) {
            $categories = Categires::DATA;

            $categoryIds = [];
            foreach ($categories[0]['categories'] as $categoryData) {
                if ($categoryData['id'] == $categoryId) {
                    foreach ($categoryData['categories'] as $category) {
                        $categoryIds[] = $category['id'];
                    }
                }
            }
        } else {
            $categoryIds[] = $categoryId;
        }

        return $categoryIds;
    }

    public function getStoreCountByCategoryAndCity($index, $categoryId, $cityId)
    {

        $body = [
            "query" => [
                "bool" => [
                    "filter" => [
                        [
                            "term" => [
                                "category_id_2" => [
                                    "value" => $categoryId
                                ]
                            ]
                        ],
                        [
                            "term" => [
                                "city_id" => [
                                    "value" => $cityId
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "aggs" => [
                "id_count" => [
                    "cardinality" => [
                        "field" => "_id"
                    ]
                ]
            ]
        ];

        $data = app('es')->searchDoc($index, '_doc', $body);

        return $data['aggregations']['id_count']['value'] ?? 0;
    }

    public function getStoreById($id, $index, $latitude, $longitude)
    {
        $body = [
            "query" => [
                "bool" => [
                    "filter" => [
                        [
                            "geo_distance" => [
                                "distance" => 500000000,
                                "location" => [
                                    "lat" => $latitude,
                                    "lon" => $longitude
                                ]
                            ]
                        ],
                        [
                            "term" => [
                                "_id" => [
                                    "value" => $id
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "sort" => [
                [
                    "_geo_distance" => [
                        "location" => [
                            "lat" => $latitude,
                            "lon" => $longitude
                        ],
                        "order" => "asc",
                        "unit" => 'm'
                    ]
                ]
            ]
        ];

        $data = app('es')->searchDoc($index, '_doc', $body);
        $storeInfo = $data['hits']['hits'][0] ?? [];
        return $storeInfo;

    }

    public function getSrcStoreByCity($index, $cityId, $brandId)
    {

        $body = [
            "query" => [
                "bool" => [
                    "filter" => [
                        [
                            "term" => [
                                "src_city_id" => [
                                    "value" => $cityId
                                ]
                            ]
                        ],
                        [
                            "term" => [
                                "src_brand_id" => [
                                    "value" => $brandId
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "_source" => ["id", "branch_name", 'name']

        ];

        $data = app('es')->searchDoc($index, '_doc', $body);

        $esStores = $data['hits']['hits'];
        $stores = [];

        foreach ($esStores as $esStore) {
            $stores[] = $esStore['_source'];
        }

        return $stores;

    }
}
