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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/item', 'ItemController@store');

Route::post('/merchant', 'MerchantController@store');

Route::post('/location', 'LocationController@store');

Route::post('/category', 'CategoryController@store');

Route::post('/image', 'ImageController@store');
