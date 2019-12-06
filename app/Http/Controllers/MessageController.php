<?php

namespace App\Http\Controllers;

use App\Models\ImGroupMessage;
use App\Models\ImMessage;
use App\Models\ImGroup;
use App\Models\Category;
use App\Models\UserInfo;
use function foo\func;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function dialog(Request $request)
    {

//        $page = $request->get('page', 1);
//        $pageSize = $request->get('pageSize', 10);
//        $offset = ($page - 1) * $pageSize;
//        $user = $request->user();
//        $userId = $user->id;
//        $messages = ImMessage::query()->where('type', 1)
//            ->where(function ($query) use ($userId) {
//                $query->where('from_id', '=', $userId)->orWhere('to_id', '=', $userId);
//            })
//            ->where('is_deleted', 0)->where('status', 0)
//            ->offset($offset)->limit($pageSize)
//            ->orderBy('id', 'desc')->orderBy('created_at', 'desc')
//            ->with('relateUser')->get()->toArray();
//
//        return $this->jsonOk($messages);

    }


    public function group(Request $request)
    {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;
        $groupId = $request->post("group_id");
        $messages = ImGroupMessage::query()->where('group_id', $groupId)
            ->where('is_deleted', 0)->where('status', 0)
            ->offset($offset)->limit($pageSize)
            ->orderBy('id', 'desc')->orderBy('created_at', 'desc')
//            ->with('relateUser')
            ->get()->toArray();

        $column = ['id', 'name', 'created_at', 'avatar'];
        $group = ImGroup::query()->where('id', $groupId)
            ->get($column)->toArray();
        $data = [
            'groupInfo' => $group,
            'groupMsg' => $messages,
        ];
        return $this->jsonOk($data);
    }

    public function system(Request $request)
    {

    }

    public function create(Request $request)
    {
        $model = new ImMessage($request->all());
        if ($model->validate($request->all())) {
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

    public function GroupCreate(Request $request)
    {
        $model = new ImGroupMessage($request->all());
        if ($model->validate($request->all())) {
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

    public function Unread(Request $request)
    {
//        $count = ImGroupMessage::query()->where()

        $data = ['single' => 6, 'group' => 19, 'system' => 2];
        return $this->jsonOk($data);
    }

    public function autoCreate()
    {
        for ($i = 0; $i < 10; $i++) {
            $model = new ImMessage();
            $model->relate_id = 0;
            $model->from_id = 3;
            $model->to_id = 6;
            $model->answer_id = 0;
            $model->content = '消息' . mt_rand(1, 10000);
            $model->type = 1;
            $model->status = 0;
            $model->save();

            $model = new ImMessage();
            $model->relate_id = 0;
            $model->from_id = 6;
            $model->to_id = 3;
            $model->answer_id = 0;
            $model->content = '消息' . mt_rand(1, 10000);
            $model->type = 1;
            $model->status = 0;
            $model->save();
        }

    }
}
