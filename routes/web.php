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
Route::get("site/option", ['as' => 'site.option', 'uses' => 'SiteController@option']);
Route::get("site/test", ['as' => 'site.test', 'uses' => 'SiteController@test']);


Route::get("group/index", ['as' => 'group.index', 'uses' => 'GroupController@index']);
Route::post("group/create", ['uses' => 'GroupController@create']);
Route::get('group/members', ['uses' => 'GroupController@members']);
Route::post('group/join', ['uses' => 'GroupController@join']);


// 分类
Route::get("category/index", ['uses' => 'CategoryController@index']);
Route::post("category/create", ['uses' => 'CategoryController@create']);

//图片管理
Route::get('image/index', ['uses' => 'ImageController@index']);
Route::post('image/create', ['uses' => 'ImageController@create']);
Route::post('image/upload', ['uses' => 'ImageController@upload']);
Route::options('image/index', function () {
    return "";
});

//登陆注册用户
Route::post("login/code", ['uses' => 'LoginController@code']);
Route::post("login/web", ['uses' => 'LoginController@web']);


Route::get('message/autocreate', ['uses' => 'MessageController@autocreate']);
Route::post('message/create', ['uses' => 'MessageController@create']);
Route::post('message/groupCreate', ['uses' => 'MessageController@groupCreate']);

Route::get("user/onlineCount", ['uses' => 'UserController@onlineCount']);
//Route::post("user/login", ['uses' => 'UserController@login']);
Route::get("user/info", ['uses' => 'UserController@info']);
Route::post("user/logout", ['uses' => 'UserController@logout']);
Route::post("user/register", ['uses' => 'UserController@register']);

//后台
Route::get('admin/test', ['uses' => 'Admin\UserController@test']);
Route::post('admin/login', ['uses' => 'Admin\UserController@login']);
Route::get('admin/info', ['uses' => 'Admin\UserController@info']);

//客户
Route::get('customer', ['uses' => 'Admin\CustomerController@index']);
Route::post('customer/create', ['uses' => 'Admin\CustomerController@create']);
Route::post('customer/update', ['uses' => 'Admin\CustomerController@update']);

//product 产品死亡目录 API
Route::options('product/create', 'Product\ProductController@options');
Route::get('product/test', ['uses' => 'Product\ProductController@test']);
Route::get('product', ['uses' => 'Product\ProductController@list']);
Route::post('product/create', ['uses' => 'Product\ProductController@create']);
Route::get('product/detail', 'Product\ProductController@detail');
Route::post('product/update', ['uses' => 'Product\ProductController@update']);


Route::post('productDetail/create', ['uses' => 'Product\ProductDetailController@create']);


Auth::routes(); //vendor\laravel\framework\src\Illuminate\Routing\Router.php

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', function () {
    echo "profile";
})->middleware('auth');

