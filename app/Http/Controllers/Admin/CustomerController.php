<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\OSS;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Image;
use App\Models\ImGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $categoryId = $request->get('category_id', 0);

        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 9);
        $offset = ($page - 1) * $pageSize;
        /** @var Customer $query */
        $query = Customer::query()->where('is_deleted', 0);

        $customers = $query->offset($offset)->limit($pageSize)
//            ->orderBy('category_id', 'asc')
            ->orderBy('id', 'desc')->get();

        $count = $query->count();
        return $this->jsonOk(['list' => $customers, 'total' => $count]);
    }

    public function create(Request $request)
    {
        $model = new Customer($request->all());
        if ($model->validate($request->all())) {
            if (!$model->save()) {
                throw new \RuntimeException("保存插入失败");
            }
            $data[] = $model;
        } else {
            $message = $model->errors[0] ?? "未知错误";
            return $this->jsonOk([], '加入失败！' . $message);
        }

        return $this->jsonOk($data, '添加成功');
    }

    public function import(Request $request) {

    }

    public function update(Request $request) {
        $id = $request->get('id');
        $model = Customer::query()->where('id', $id)->where('is_deleted', 0)
            ->first();
        if($model == null){
           return $this->jsonErr(100008, '未找到此模型');
        }

        $model->fill($request->all());
        $rule = [
            'status' => 'int|in:0,1,-1',
        ];

        if ($model->validate($request->all(), $rule)) {
            // 验证组是否存在
            $status = $model->save();
            if (!$status) {
                throw new \RuntimeException("更新失败");
            }
            return $this->jsonOk($model, '更新成功！');
        } else {
            $message = $model->errors[0] ?? '未知错误';
            return $this->jsonOk([], '加入失败！' . $message);
        }

    }
}
