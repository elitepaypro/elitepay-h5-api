<?php

namespace App\Http\Services;

use App\Constants\ShopInfo;
use App\Models\TbRegion;
use App\Models\TbRegionMapping;
use App\Models\TbSrcRegion;

class CityService
{

    public function getCityByLatAndLon($latitude, $longitude)
    {
        $index = config('app.current_index');
        $tbRegion = new TbRegion;

        $body = [
            "size" => 1,
            "query" => [
                "bool" => [
                    'must' => [
                        [
                            "geo_distance" => [
//                                    "distance_type" => "sloppy_arc",
                                "distance" => 3000,
                                "location" => [
                                    "lat" => $latitude,
                                    "lon" => $longitude
                                ],
                                "unit" => 'm'
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

        $data = current($data['hits']['hits'])['_source'];

        $city = $tbRegion->where('id', $data['city_id'])->first();

        return $city;
    }

    public function getSrcRegion($regionId, $shopId)
    {
        $regionMappingModel = new TbRegionMapping();
        $srcRegionModel = new TbSrcRegion();

        $srcRegionIds = $regionMappingModel->where('region_id', $regionId)->get(['src_region_id']);

        $srcRegion = $srcRegionModel->whereIn('id', $srcRegionIds)->where('level', 3)->where('shopid', $shopId)->first();

        return $srcRegion;
    }
}
