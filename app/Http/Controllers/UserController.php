<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use App\Models\UserRelation;
use Illuminate\Http\Request;

class UserController extends Controller
{

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

    public function create(Request $request)
    {


    }


    public function update(){

    }
}
