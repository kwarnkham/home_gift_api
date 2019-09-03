<?php

use Illuminate\Http\Request;

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

/*
|--------------------------------------------------------------------------
| API return code infomation
|--------------------------------------------------------------------------
|
| 0 - success
| 1 - handled issue
| 2 - token issue
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/item', 'ItemController@store');
Route::get('/items', 'ItemController@index');
Route::post('/item/category/add', 'ItemController@addCategory');
Route::post('/item/category/remove', 'ItemController@removeCategory');
Route::put('/item/name', 'ItemController@updateName');

Route::post('/merchant', 'MerchantController@store');
Route::get('/merchants', 'MerchantController@index');
Route::put('/merchant', 'MerchantController@update');

Route::post('/location', 'LocationController@store');
Route::get('/locations', 'LocationController@index');
Route::put('/location', 'LocationController@update');

Route::post('/category', 'CategoryController@store');
Route::get('/categories', 'CategoryController@index');
Route::put('/category', 'CategoryController@update');

Route::post('/image', 'ImageController@store');



Route::get('/test', function () {
    return json_encode(['message' => 'success']);
});
