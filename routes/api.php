<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::match(['post', 'get'], '/store/search', [\App\Http\Controllers\StoreController::class, 'search']); // 商铺搜索
Route::resource('/store', \App\Http\Controllers\StoreController::class); // 商铺
Route::get('/bank', '\App\Http\Controllers\CityController@bank'); // 银行卡
Route::get('/categories', '\App\Http\Controllers\CityController@categories'); // 分类
Route::get('/indexSync/store', '\App\Http\Controllers\StoreController@indexSync'); // 索引同步
Route::get('/indexSync/srcStore', '\App\Http\Controllers\StoreController@indexSyncSrcStore'); // 源数据索引同步
Route::resource('/city', \App\Http\Controllers\CityController::class); // 城市
Route::get('/get-city', '\App\Http\Controllers\CityController@getCityByLocation'); // 查询城市城市
Route::resource('/search', \App\Http\Controllers\SearchController::class); // 热门搜索
Route::resource('/coupon', \App\Http\Controllers\CouponController::class); // 优惠券
Route::get('/count/store-city-category', '\App\Http\Controllers\StoreController@countStoreCityCategory'); // 汇总城市分类店铺总数
Route::get('/group-purchase', '\App\Http\Controllers\CouponController@groupPurchase'); // 团购
Route::match(['get', 'post'], '/coupon-proxy', '\App\Http\Controllers\CouponController@proxyCoupon'); // 优惠券代理接口

Route::match(['get', 'post'], '/src-city', '\App\Http\Controllers\StoreController@getCityByShop'); // 获取城市店铺数据
Route::match(['get', 'post'], '/store-city', '\App\Http\Controllers\StoreController@getSrcStoreBySrcCity'); // 获取城市店铺数据

