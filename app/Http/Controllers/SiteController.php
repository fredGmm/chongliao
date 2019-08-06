<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\UserInfo;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class SiteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('guest')->except('logout');
//    }

    public function option()
    {
        echo date("H:i;s") . "hello world";exit;
    }

    public function test()
    {
        echo "start";
//        $categories =  Category::all();
//        foreach ($categories as $category) {
//            echo $category->name;
//        }
       $url = 'https://api.weixin.qq.com/sns/jscode2session';
//       $client = new Client();
//        $response = $client->request('GET', $url, ['query' => [
//            'appid' => 'wxc0b949e81c79d847',
//            'secret' => 'eb6e87629fcdadd2328569dd42d236e2',
//            'grant_type' => 'authorization_code',
//            'js_code' => '081DlOSo0kzCUk1fmeUo06WRSo0DlOSm'
//        ]]);
//
//
//// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;
//
//        $statusCode = $response->getStatusCode();
//        $content = $response->getBody();
//        $query = [
//            'appid' => 'wxc0b949e81c79d847',
//            'secret' => 'eb6e87629fcdadd2328569dd42d236e2',
//            'grant_type' => 'authorization_code',
//            'js_code' => '081DlOSo0kzCUk1fmeUo06WRSo0DlOSm'
//        ];
//
//        $result = Common::curl($url,'GET', http_build_query($query));
//        var_dump($result);
//        exit;
        $userModel = UserInfo::query()->where('id', 100000000000)->first();

        var_dump($userModel);
        exit;


    }
}
