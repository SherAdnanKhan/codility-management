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
Route::group(['middleware' => ['api'],'prefix' => 'auth'], function () {
    Route::post('login', 'Api\AuthController@authenticate');
});
Route::group(['middleware' => ['api','verify-token'],'prefix' => 'auth'], function () {
    Route::post('logout', 'Api\AuthController@logout');
});