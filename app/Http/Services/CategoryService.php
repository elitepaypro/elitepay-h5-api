<?php

namespace App\Http\Services;

use App\Constants\Categires;
use App\Models\TbRegion;
use Illuminate\Support\Facades\Redis;

class CategoryService
{
    // 根据城市和分类汇总店铺
    public function storeCountByCityAndCategory()
    {
        // 先获取所有城市
        $tbRegion = new TbRegion();
        $storeService = new StoreService();
        $index = config('app.current_index', 'store_3');

        $cites = $tbRegion->where('level', 3)->where('status', 1)->get();
        $categories = Categires::DATA[0];

        $counts = [];

        foreach ($cites as $city) {
            $cityId = $city->id;
            $cityRedisKey = "store:count:{$cityId}";

            if (!Redis::get($cityRedisKey)) {
                foreach ($categories['categories'] as $categoryGroup) {
                    foreach ($categoryGroup['categories'] as $category) {

                        $categoryId = $category['id'];

                        $count = $storeService->getStoreCountByCategoryAndCity($index, $categoryId, $cityId);

                        $redisKey = "store:count:{$cityId}:{$categoryId}";

                        $counts[] = $redisKey . ":{$count}";

                        Redis::set($redisKey, $count);
                    }
                }

                Redis::setex($cityRedisKey, 86400, 1);
            }
        }

        return $counts;
    }

    public function getCategoryByCity($cityId)
    {
        $categories = Categires::DATA;

        foreach ($categories as $key1 => $categories1) {
            foreach ($categories1['categories'] as $key2 => $categories2) {
                foreach ($categories2['categories'] as $key3 => $category) {
                    $categoryId = $category['id'];

                    $redisKey = "store:count:{$cityId}:{$categoryId}";
                    $count = Redis::get($redisKey) ?? 0;

                    $categories[$key1]['categories'][$key2]['categories'][$key3]['store_count'] = (int)$count;
                }
            }

        }

        return $categories;
    }
}
