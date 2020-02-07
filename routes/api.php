<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/provinces', 'ProvinceController@index');

Route::get('/items', 'ItemController@index');
Route::get('/item/{id}', 'ItemController@find');
Route::get('/items/trashed', 'ItemController@indexTrashed');
Route::get('/items/find/name/{name}', 'ItemController@findByName');
Route::get('/items/find/category/{categoryId}', 'ItemController@findByCategory');
Route::get('/items/find/location/{locationId}', 'ItemController@findByLocation');
Route::get('/items/find/merchant/{merchantId}', 'ItemController@findByMerchant');
Route::get('/items/find/province/{provinceId}', 'ItemController@findByProvince');
// Route::get('/items/find/{}')


Route::post('/item', 'ItemController@store');
Route::post('/item/{id}/{categoryId}', 'ItemController@addCategory');
Route::put('/item/{id}/categories', 'ItemController@updateCategory');
Route::patch('item/{id}', 'ItemController@unDestroy');
Route::put('/item/{id}', 'ItemController@update');
Route::delete('/item/{id}', 'ItemController@destroy');

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
Route::delete('/image/{id}', 'ImageController@destroy')->where('id', '[0-9]+');

Route::post('/user', 'UserController@store');
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout')->middleware('auth:api');
Route::get('/user', 'UserController@show')->middleware('auth:api')->name('checkToken');
Route::get('/tokenExpired', 'UserController@reponseToInvalidToken')->name('tokenExpired');

Route::post('/order', 'OrderController@store')->middleware('auth:api');
Route::get('/order/user', 'OrderController@userOrder')->middleware('auth:api');
Route::get('/orders', 'OrderController@index');
Route::post('/order/action/{id}', 'OrderController@update')->where('id', '[0-9]+');
