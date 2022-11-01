<?php

namespace App\Http\Controllers;

use App\Models\SearchWord;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(SearchWord $searchWord)
    {
//        $data = $searchWord->where('status', 1)->orderBy('search_num', 'desc')->limit(10)->get();

        return [
            'code' => 1000,
            'data' => [
                "肯德基", "星巴克", "麦当劳", "蜜雪冰城", "全家", "海底捞"
            ]
        ];
    }
}
