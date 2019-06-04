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
    return "hello,chongliao!";
});


//Route::get('/', ['as'=>'website.index','uses'=>'IndexController@index']);

// 首页站点配置信息
Route::get("site/option", ['as'=>'site.option', 'uses' => 'SiteController@option']);
Route::get("site/test", ['as'=>'site.test', 'uses' => 'SiteController@test']);



Route::get("group/index", ['as'=>'group.index', 'uses' => 'GroupController@index']);
Route::post("group/create", ['uses' => 'GroupController@create']);


// 分类
Route::get("category/index", ['uses' => 'CategoryController@index']);
Route::post("category/create", ['uses' => 'CategoryController@create']);

//登陆注册用户
Route::post("login/code", ['uses' => 'LoginController@code']);


Route::get('message/autocreate', ['uses' => 'MessageController@autocreate']);
Route::post('message/create', ['uses' => 'MessageController@create']);
Route::post('message/groupCreate', ['uses' => 'MessageController@groupCreate']);



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', function() {
   echo "profile";
})->middleware('auth');