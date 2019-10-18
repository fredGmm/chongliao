<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function test()
    {
        echo 'test';
        exit;
    }


    public function list(Request $request)
    {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;

        //处理排序
        $order = $request->get('sort', '-death_time');
        $desc = strrpos('-', $order) === false ? 'asc' : 'desc';

        //筛选
        $type = $request->get('type', '');
        $query = Product::query()->where(['is_deleted' => 0]);
        if($type) {
            $query->where('type', $type);
        }
        $list = $query->offset($offset)->limit($pageSize)->orderBy(ltrim($order, '-'), $desc)
            ->get();
        $count = $query->count();

        return $this->jsonOk(['list' => $list, 'count' => $count]);
    }

    public function create(Request $request)
    {
        $model = new Product($request->all());

        if ($model->validate($request->all())) {
            if (!$model->save()) {
                throw new \RuntimeException("插入失败");
            }
            return $this->jsonOk($model, '添加成功！');
        } else {
            $message = $model->errors[0] ?? '位置错误';
            return $this->jsonOk([], '添加失败！' . $message);
        }
    }



    public function update(){

    }

    public function delete(){

    }








}
