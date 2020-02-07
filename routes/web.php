<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test/pay','TestController@alipay');  // 去支付

Route::get('/test/alipay/return','Alipay\PayController@aliReturn');
Route::post('/test/alipay/notify','Alipay\PayController@notify');

Route::get('/test/ascii','TestController@ascii');
Route::get('/test/dec','TestController@dec');
Route::get('/test/md1','TestController@md1');

Route::get('/api/test','Api\TestController@test');
Route::post('/api/user/reg','Api\TestController@reg');

// 签名测试
Route::get('/sign1','TestController@sign1');
Route::get('/test/sign2','TestController@sign2');

//防刷
Route::get('/test/postman','Api\TestController@postman');
Route::get('/test/postman1','Api\TestController@postman1')->middleware('filter','check.token');

Route::get('/test/md5','Api\TestController@md5test');

Route::get('/test/sign3','Api\TestController@sign3');
Route::get('/test/sign4','Api\TestController@sign4');   // 私钥

