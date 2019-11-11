<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/provinces', 'ProvinceController@index');

Route::post('/item', 'ItemController@store');
Route::get('/items', 'ItemController@index');
Route::put('/item/{id}/categories', 'ItemController@updateCategory');
Route::put('item/{id}', 'ItemController@update');

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
