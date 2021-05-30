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
    return "hello,chongliao!" .  md5('daoneng123456');;
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
Route::get('image/detail', ['uses' => 'ImageController@detail']);
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
Route::get('admin/image', ['uses' => 'Admin\ImageController@index']);
Route::put('admin/image', ['uses' => 'Admin\ImageController@update']);
Route::post('admin/image', ['uses' => 'Admin\ImageController@create']);
Route::options('admin/image', 'Admin\ImageController@options');

//客户
Route::get('customer', ['uses' => 'Admin\CustomerController@index']);
Route::post('customer/create', ['uses' => 'Admin\CustomerController@create']);
Route::put('customer', ['uses' => 'Admin\CustomerController@update']);

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


//道能
Route::get('dn/activity', ['uses' => 'Dn\DnController@activity_list']);
Route::post('dn/activity_delete', ['uses' => 'Dn\DnController@activity_delete']);

Route::get('dn/article', ['uses' => 'Dn\DnController@article_list']);
Route::post('dn/article_delete', ['uses' => 'Dn\DnController@article_delete']);
Route::post('dn/article_create', ['uses' => 'Dn\DnController@article_create']);
Route::get('dn/article_detail', 'Dn\DnController@article_detail');
Route::post('dn/article_update', ['uses' => 'Dn\DnController@article_update']);

Route::get('dn/user', ['uses' => 'Dn\DnController@user_list']);
Route::post('dn/user_create', ['uses' => 'Dn\DnController@user_create']);
Route::post('dn/user_delete', ['uses' => 'Dn\DnController@user_delete']);
Route::get('dn/user_detail', 'Dn\DnController@user_detail');
Route::put('dn/user_update', ['uses' => 'Dn\DnController@user_update']);

Route::get('dn/user_community', ['uses' => 'Dn\DnController@community_list']);
Route::post('dn/user_community_delete', ['uses' => 'Dn\DnController@community_delete']);


Route::get('dn/banner_detail', 'Dn\DnController@banner_detail');
Route::get('dn/banner_list', ['uses' => 'Dn\DnController@banner_list']);
Route::post('dn/banner_update', ['uses' => 'Dn\DnController@banner_update']);
Route::post('dn/banner_create', ['uses' => 'Dn\DnController@banner_create']);
Route::post('dn/banner_delete', ['uses' => 'Dn\DnController@banner_delete']);

Route::get('dn/clock_main_class', ['uses' => 'Dn\DnController@clock_main_class']);
Route::get('dn/clock_main_class_detail', 'Dn\DnController@clock_main_class_detail');
Route::post('dn/clock_main_class_update', ['uses' => 'Dn\DnController@clock_main_class_update']);
Route::post('dn/clock_main_class_create', ['uses' => 'Dn\DnController@clock_main_class_create']);
Route::post('dn/clock_main_class_delete', ['uses' => 'Dn\DnController@clock_main_class_delete']);


Route::get('dn/clock_class', ['uses' => 'Dn\DnController@clock_class']);
Route::get('dn/clock_main_class_detail', 'Dn\DnController@clock_main_class_detail');
Route::post('dn/clock_class_update', ['uses' => 'Dn\DnController@clock_class_update']);
Route::post('dn/clock_class_create', ['uses' => 'Dn\DnController@clock_class_create']);
Route::post('dn/clock_class_delete', ['uses' => 'Dn\DnController@clock_class_delete']);

Route::get('dn/clock_records', ['uses' => 'Dn\DnController@clock_records']);


Route::get('/dn/dashboard_panel', ['uses' => 'Dn\DnController@dashboard_panel']);
