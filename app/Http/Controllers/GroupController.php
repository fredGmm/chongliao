<?php

namespace App\Http\Controllers;

use App\Models\ImGroup;
use App\Models\Category;
use App\Models\ImGroupMember;
use App\Models\UserInfo;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 6);
        $offset = ($page - 1) * $pageSize;
        $column = ['id', 'name', 'avatar', 'cover', 'creator', 'type', 'created_at', 'updated_at'];
        $groupList = ImGroup::query()->where('is_deleted', 0)
            ->offset($offset)->limit(1)->get($column)->toArray();
//        $privateList = [
//            ['time' => time(), 'other_user_id' => 23,
//                'avatar' => 'https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png',
//                'unread' => 2, 'message' => 'tttttttt'
//            ],
//        ];
        $relateLite = [
            'groupList' => $groupList,
            'privateList' => [],
        ];
        return $this->jsonOk($relateLite);
    }

    public function create(Request $request)
    {
        $model = new ImGroup($request->all());
        if ($model->validate($request->all())) {
            $avatarFile = $request->file('avatar');
            if ($avatarFile) {
                $prefix = '/opt/data/chongliao';
                $path = '/group/avatar' . date('/Y/m/d/His/');
                $name = $model->name . $avatarFile->getClientOriginalExtension();
                $fullPath = $avatarFile->storeAs($prefix . $path . $name, $name);
                if ($fullPath) {
                    $model->avatar = $path;
                }
            }

            $coverFile = $request->file('cover');
            if ($coverFile) {
                $prefix = '/opt/data/chongliao';
                $path = '/group/cover' . date('/Y/m/d/His/');
                $name = $model->name . $coverFile->getClientOriginalExtension();
                $fullPath = $coverFile->storeAs($prefix . $path . $name, $name);
                if ($fullPath) {
                    $model->cover = $path;
                }
            }

            $status = $model->save();
            if (!$status) {
                throw new \RuntimeException("插入失败");
            }
            return $this->jsonOk($model, '添加成功！');
        } else {
            $message = $model->errors[0] ?? '位置错误';
            return $this->jsonOk([], '添加失败！' . $message);
        }
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function members(Request $request)
    {
        $user = $request->user();
        $groupId = $request->get("group_id");

        $members = ImGroupMember::query()->where('group_id', $groupId)
            ->where('is_deleted', 0)->get(["user_id"])->toArray();

        return $this->jsonOk(array_column($members, 'user_id'));
    }

    public function join(Request $request)
    {
        $model = new ImGroupMember($request->all());
        if ($model->validate($request->all())) {
            // 验证组是否存在
            $status = $model->save();
            if (!$status) {
                throw new \RuntimeException("插入失败");
            }
            return $this->jsonOk($model, '加入成功！');
        } else {
            $message = $model->errors[0] ?? '位置错误';
            return $this->jsonOk([], '加入失败！' . $message);
        }
    }


}
