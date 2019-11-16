<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Models\UserInfo;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;
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

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function web(Request $request){

        if(!$this->validateLogin($request)) {
            return $this->jsonErr(10001, '非法登录!');
        }

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            return $this->jsonOk(['userInfo' => $this->guard()->user()]);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->jsonErr(10001, '用户名或者密码错误!');
    }

    public function username()
    {
        return 'name';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'email' => 'required|string|email'
        ];
        $message = [
        ];

        $v = Validator::make($request->all(),$rules);
        if ($v->fails()) {
            return false;
        }
        return true;
    }
}
