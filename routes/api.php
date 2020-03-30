<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/delivery-fees', 'DeliveryFeesController@find');
Route::get('/all-delivery-fees', 'DeliveryFeesController@index');
Route::put('/delivery-fees', 'DeliveryFeesController@update');
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

Route::post('/province', 'ProvinceController@store');
Route::get('/provinces', 'ProvinceController@index');
// Route::put('/location', 'LocationController@update');

Route::post('/category', 'CategoryController@store');
Route::get('/categories', 'CategoryController@index');
Route::put('/category', 'CategoryController@update');

Route::middleware('throttle:500,1')->group(function () {
    Route::put('/category/make-a/{id}', 'CategoryController@makeCategoryA');
    Route::get('/categories/get-a', 'CategoryController@indexCategoryA');
    Route::put("/category/unmake-a/{id}", 'CategoryController@unMakeCategoryA');

    Route::get("/categories/get-b", 'CategoryController@indexCategoryB');
    Route::post('/category/make-b/{id}', 'CategoryController@makeCategoryB');
    Route::delete('b-category/{id}', 'CategoryController@destroyCategoryB');
    Route::post('/category/join-ab/{aId}/{bId}', 'CategoryController@joinAB');
    Route::get('/category/joinedA/{bId}', 'CategoryController@getJoindA');
    Route::get('/category/AB', 'CategoryController@getAB');
    Route::delete('category/unjoin-ab/{bId}', 'CategoryController@unJoinAB');
});


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
