<?php

namespace App\Http\Controllers;

use App\Constants\Categires;
use App\Http\Services\CategoryService;
use App\Http\Services\CityService;
use App\Models\TbBank;
use App\Models\TbCategory;
use App\Models\TbRegion;
use App\Utils\PyFormat;
use Illuminate\Http\Request;
use function React\Promise\all;

class CityController extends Controller
{
    public function index(TbRegion $tbRegion)
    {
        $cites = $tbRegion->where('level', 3)->where('status', 1)->get();

        $alphabets = ["A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "W", "X", "Y", "Z"];
        $data = [
            "alphabet" => $alphabets,
            "cityList" => []
        ];


        foreach ($alphabets as $alphabet) {
            $cityGroup = [
                'idx' => $alphabet,
                'cities' => []
            ];
            foreach ($cites as $city) {
                if ($city->name) {
                    try {
                        $cityName = mb_substr($city->name, 0, 1, 'utf-8');
                        $pinYin = strtoupper(PyFormat::encode($cityName)[0]);
                        if ($pinYin == $alphabet) {
                            $cityGroup['cities'][] = $city;
                        }
                    } catch (\Exception $e) {
                    }
                }
            }

            $data['cityList'][] = $cityGroup;
        }

        return [
            'code' => 1000,
            'data' => $data
        ];
    }

    public function getCityByLocation(CityService $cityService)
    {
        $requestData = request()->all(['latitude', 'longitude']);

        return [
            'code' => 1000,
            'data' => $cityService->getCityByLatAndLon($requestData['latitude'], $requestData['longitude'])
        ];
    }

    public function bank(TbBank $tbBank)
    {
        $data = $tbBank->select(['id', 'full_name', 'short_name'])->orderBy('id', 'asc')->get()->toArray();

        foreach ($data as $key => $datum) {
            if ($datum['id'] == '1000') {
                unset($data[$key]);
                break;
            }
        }
        sort($data);
        return [
            'code' => 1000,
            'data' => $data
        ];
    }

    public function categories(CategoryService $categoryService)
    {
//        $categories = $tbCategory->select(['id', 'parent_id', 'name'])->with(['categories', 'categories.categories'])->where('parent_id', 0)->get();

        $cityId = request('city_id');

        return [
            'code' => 1000,
            'data' => $categoryService->getCategoryByCity($cityId)
        ];
    }
}
