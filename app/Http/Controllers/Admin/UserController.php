<?php
/**
 * Created by PhpStorm.
 * User: fredgui
 * Date: 2019/12/13
 * Time: 10:23
 */

namespace App\Http\Controllers\Admin;

use App\Models\AdminUser;
use Illuminate\Http\Request;

class UserController extends \App\Http\Controllers\Controller
{
    public function options()
    {
        $a = str_random(32);
        return $this->jsonOk([$a]);
    }


    public function login(Request $request)
    {
        $userName = $request->post('username', '');
        $password = $request->post('password', '');

        //后台验证，可以粗暴一些
        $user = AdminUser::query()->where('username', $userName)
            ->where('password', md5($password))->where('is_deleted',0)
            ->first();
        if($user) {
            return $this->jsonOk($user);
        }else {
            $message = "账号或者密码错误";
            return $this->jsonErr(90001,$message);
        }
//        if ($userName == 'admin' && $password == '123456') {
//            $data = [
//                'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
//                'introduction' => 'I am a super administrator',
//                'name' => 'Super Admin',
//                'roles' => ['product'],
//                'token' => 'aaaaaaa'
//            ];
//            return $this->jsonOk($data, '', 0);
//        } else {
//
//            return $this->jsonErr(90001, '90001:用户名或者密码错误');
//        }
    }

    public function info(Request $request)
    {
//        $token = $request->bearerToken();
        $token = $request->get("token");
        $userModel = AdminUser::query()->where('token' , $token)->first();
        if($userModel) {
            $data = [
                'name' => $userModel->name,
                'token' => $userModel->token,
                'avatar' => $userModel->avatar,
                'roles' => [$userModel->role],
                'introduction' => '管理员'
            ];
            return $this->jsonOk($data, '', 0);
        }else{
            $message = "非管理员禁止登入";
            return $this->jsonErr(90001,$message);
        }

    }

}