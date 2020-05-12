<?php

use Illuminate\Http\Request;

Route::middleware(['auth:api', 'checkAdmin'])->group(function () {
    Route::put('/delivery-fees', 'DeliveryFeesController@update');

    Route::post('/item/{id}/{categoryId}', 'ItemController@addCategory');
    Route::put('/item/{id}/categories', 'ItemController@updateCategory');
    Route::patch('item/{id}', 'ItemController@unDestroy');
    Route::put('/item/{id}', 'ItemController@update');
    Route::delete('/item/{id}', 'ItemController@destroy');
    Route::post('/item', 'ItemController@store');

    Route::put('/merchant', 'MerchantController@update');
    Route::post('/merchant', 'MerchantController@store');

    Route::post('/location', 'LocationController@store');
    Route::put('/location', 'LocationController@update');
    Route::post('/province', 'ProvinceController@store');

    Route::put('/category/make-a/{id}', 'CategoryController@makeCategoryA');
    Route::put("/category/unmake-a/{id}", 'CategoryController@unMakeCategoryA');
    Route::post('/category', 'CategoryController@store');
    Route::put('/category', 'CategoryController@update');
    Route::post('/category/make-b/{id}', 'CategoryController@makeCategoryB');
    Route::delete('b-category/{id}', 'CategoryController@destroyCategoryB');
    Route::post('/category/join-ab/{aId}/{bId}', 'CategoryController@joinAB');
    Route::delete('category/unjoin-ab/{bId}', 'CategoryController@unJoinAB');
    Route::post('category/join-bc/{bId}/{id}', 'CategoryController@joinBC');
    Route::delete('category/unjoin-bc/{id}', 'CategoryController@unJoinBC');

    Route::post('/image', 'ImageController@store');
    Route::delete('/image/{id}', 'ImageController@destroy')->where('id', '[0-9]+');

    Route::post('/order/action/{id}', 'OrderController@update')->where('id', '[0-9]+');
    Route::get('/orders', 'OrderController@index');
});
Route::get('/delivery-fees', 'DeliveryFeesController@find');
Route::get('/all-delivery-fees', 'DeliveryFeesController@index');

Route::get('/items', 'ItemController@index');
Route::get('/item/{id}', 'ItemController@find');
Route::get('/items/trashed', 'ItemController@indexTrashed');
Route::get('/items/find/name/{name}', 'ItemController@findByName');
Route::get('/items/find/category/{categoryId}', 'ItemController@findByCategory');
Route::get('/items/find/location/{locationId}', 'ItemController@findByLocation');
Route::get('/items/find/merchant/{merchantId}', 'ItemController@findByMerchant');
Route::get('/items/find/province/{provinceId}', 'ItemController@findByProvince');

Route::get('/merchants', 'MerchantController@index');
Route::get('/locations', 'LocationController@index');
Route::get('/provinces', 'ProvinceController@index');
Route::get('/categories', 'CategoryController@index');
Route::get('/categories/get-a', 'CategoryController@indexCategoryA');
Route::get("/categories/get-b", 'CategoryController@indexCategoryB');
Route::get("/categories/get-b/{id}", 'CategoryController@getBCategoriesOfA');
Route::get('/category/joinedA/{bId}', 'CategoryController@getJoinedA');
Route::get('/category/AB', 'CategoryController@getAB');
Route::get('/category/BC', 'CategoryController@getBC');
Route::get('/category/joinedB/{id}', 'CategoryController@getJoinedB');
Route::get("/categories/get-c/{id}", 'CategoryController@getCCategoriesOfB');
    
Route::post('/user', 'UserController@store');
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout')->middleware('auth:api');
Route::get('/user', 'UserController@show')->middleware('auth:api')->name('checkToken');
Route::get('/tokenExpired', 'UserController@reponseToInvalidToken')->name('tokenExpired');
Route::put('/user/password', 'UserController@updatePassword')->middleware('auth:api');
Route::put('/user', 'UserController@update')->middleware('auth:api');

Route::post('/order', 'OrderController@store')->middleware('auth:api');
Route::get('/order/user', 'OrderController@userOrder')->middleware('auth:api');
Route::get('/townships', 'TownshipController@index');
Route::get('/townships/find/location/{locationId}', 'TownshipController@findByLocation');
