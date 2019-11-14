<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use App\Models\UserRelation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

//    public function options()
//    {
//        return $this->jsonOk([]);
//    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'name.unique' => '该用户名已存在!',
            'password.min' => '密码最短为6位',
        ];

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:user_info'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:user_info'],
            'password' => ['required', 'string', 'min:6'],
        ], $messages);
    }

    public function relation(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        if($request->getMethod() == 'POST') {
            $model = new UserRelation();
            $model->uid = $userId;
            $model->rid = $request->post('rid');
            $model->type = 'single';

            if($model->save()){
                return $this->jsonOk($model, '发送成功！');
            }else{
                return $this->jsonOk([], '发送失败！');
            }
        }else{
            $user = $request->user();
            $userId = $user->id;

            $list = UserRelation::query()->where('uid', $userId)
                ->where('is_deleted', 0)
                ->with("relateUser")
                ->get()->toArray();

            return $this->jsonOk($list);
        }
    }

    public function create($data)
    {
        return UserInfo::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? '',
            'password' => Hash::make($data['password']),
            'api_token' =>str_random(64)
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()) {
            return $this->jsonErr(10000, $validator->errors()->first());
        }
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        $this->guard()->login($user);
        return $this->jsonOk($user);
    }


    //随机匹配
    public function randomMatch()
    {
        $randomUsers = UserInfo::getRandom(1);
        if (empty($randomUsers->all())) {
            return $this->jsonErr(400404, '暂时没有合适的宠物主闲下来~');
        } else {
            return $this->jsonOk($randomUsers);
        }
    }

    public function update(){

    }

    public function login(Request $request){

        $userName = $request->post('username', '');
        $password = $request->post('password', '');

        if($userName == 'admin' && $password == '123456') {
            $data = [
                'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
                'introduction' => 'I am a super administrator',
                'name' => 'Super Admin',
                'roles' => ['product'],
                'token' => 'aaaaaaa'
            ];
            return $this->jsonOk($data,'', 0);
        }else{

            return $this->jsonErr(90001, '90001:用户名或者密码错误');
        }
    }

    public function info()
    {

        $data = ['token' => 'admin-token', 'avatar' => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif', 'roles' => ['product']];
        return $this->jsonOk($data,'',0);
    }

    public function onlineCount() {

        var_dump(Auth::id());
        var_dump(Auth::check());
        exit;

        $redis = app('redis.connection');
        $online = $redis->smembers("ONLINE_COUNT");

        $count = count($online);

        return $this->jsonOk($count);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
