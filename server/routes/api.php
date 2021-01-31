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

Route::post('/wx/login', 'Api\Wx@login');
Route::post('/wx/test', 'Api\Wx@test')->middleware('checktoken');
Route::post('/shop/categorylist', 'Api\Shop@cateGoryList')->middleware('checktoken');
Route::post('/shop/goodslist', 'Api\Shop@goodsList')->middleware('checktoken');
Route::post('/shop/goodsroles', 'Api\Shop@goodsRoles')->middleware('checktoken');
Route::post('/shop/addshopshop', 'Api\Shop@addShopShop')->middleware('checktoken');
Route::post('/shop/shopdetail', 'Api\Shop@shopDetail')->middleware('checktoken');
Route::post('/shop/shoppingcarinfo', 'Api\Shop@shoppingCarInfo')->middleware('checktoken');
Route::post('/shop/carinfo', 'Api\Shop@carInfo')->middleware('checktoken');
Route::post('/shop/deletecaritem', 'Api\Shop@deleteCarInfo')->middleware('checktoken');
Route::post('/shop/addcaritem', 'Api\Shop@addCarInfo')->middleware('checktoken');
Route::post('/shop/reducecaritem', 'Api\Shop@reduceCarInfo')->middleware('checktoken');
Route::post('/home/getuserinfo', 'Api\Home@getUserInfo')->middleware('checktoken');
Route::post('/home/orderstatic', 'Api\Home@orderStatic')->middleware('checktoken');
Route::post('/index/index', 'Api\Index@index')->middleware('checktoken');
Route::post('/user/modifyaddress', 'Api\User@modifyAddress')->middleware('checktoken');
Route::post('/user/defaultaddress', 'Api\User@defaultAddress')->middleware('checktoken');
Route::post('/user/queryaddress', 'Api\User@queryAddress')->middleware('checktoken');
Route::post('/user/addressdetail', 'Api\User@addressDetail')->middleware('checktoken');
Route::post('/user/deleteaddress', 'Api\User@deleteAddress')->middleware('checktoken');
Route::post('/user/updateaddress', 'Api\User@updateAddress')->middleware('checktoken');
Route::post('/order/ordercreate', 'Api\Order@orderCreate')->middleware('checktoken');
Route::post('/order/removecarinfo', 'Api\Order@removeCarInfo')->middleware('checktoken');
Route::post('/order/orderlist', 'Api\Order@orderList')->middleware('checktoken');
Route::post('/order/orderclose', 'Api\Order@orderClose')->middleware('checktoken');
