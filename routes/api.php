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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware('auth:api')->post('/user/login', 'UserController@login');
Route::middleware('auth:api')->get('/user/update', 'UserController@update');

//用户
Route::middleware('auth:api')->get('/user', 'UserController@info');


Route::middleware('auth:api')->middleware('cors')->get('/message/dialog', 'MessageController@dialog');
Route::middleware('auth:api')->get('/message/group', 'MessageController@group');

Route::middleware('auth:api')->get('/group/index', 'GroupController@index');
//Route::group(['middleware' => 'cors'], function(){
//    Route::middleware('auth:api')->get('/group/index', 'GroupController@index');
//});


Route::middleware('auth:api')->get('/user/relation', ['uses' => 'UserController@relation']);
Route::middleware('auth:api')->get('/user/randomMatch', ['uses' => 'UserController@randomMatch']);


Route::get("site/info", ['as' => 'site.option', 'uses' => 'SiteController@info']);


