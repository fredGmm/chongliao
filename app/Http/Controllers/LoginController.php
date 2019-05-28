<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    const APP_ID = '';
    const APP_SECRET = '';

    public function code(Request $request){


        $url = 'https://api.weixin.qq.com/sns/jscode2session'; // code 换取 session_key 和 open_id
        $query = [
            'appid' => 'wxc0b949e81c79d847',
            'secret' => 'eb6e87629fcdadd2328569dd42d236e2',
            'grant_type' => 'authorization_code',
            'js_code' => $request->post('code')
        ];
//        $result = Common::curl($url. '?' .  http_build_query($query),'GET', http_build_query($query));
        $result = [
            'session_key' =>  'qi2Sc4vDbVvjs66UYKOQ5A==',
            'openid' =>  'oXW8g5ZMAY3r38DnhEBQOnfMNB-c'
        ];
        //是否遇到错误
        if(isset($result['errcode']) && ($result['errcode'] != 0)) {

            $this->jsonErr($result['errcode'], $result['message']);
        }
        $token = str_random(64);
        //存入用户
        $userModel = UserInfo::query()->where('openid', $result['openid'])->first();
        if($userModel == null){
            //创建并登陆
            $userModel = new UserInfo();
            $userModel->name = $nickName ?? ('小萌新' . mt_rand(1000,2000));
            $userModel->password = Hash::make('chongliao');
            $userModel->api_token = $token;
            $userModel->openid = $result['openid'];
            $userModel->session_key = $result['session_key'] ?? '';
            $status = $userModel->save();
            var_dump($status);
        }
        return $this->jsonOk(['user' => $userModel]);
    }
}
