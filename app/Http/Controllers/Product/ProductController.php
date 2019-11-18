<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //测试
    public function test()
    {
        echo 'hello world';
        exit;
    }

    public function options()
    {
        return $this->jsonOk([]);
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
        if ($type) {
            $query->where('type', $type);
        }
        $list = $query->offset($offset)->limit($pageSize)->orderBy(ltrim($order, '-'), $desc)
            ->get();
        $count = $query->count();

        return $this->jsonOk(['list' => $list, 'total' => $count]);
    }

    public function create(Request $request)
    {
        $model = new Product($request->all());

        if ($model->validate($request->all())) {
            if (!$model->save()) {
                throw new \RuntimeException("插入失败");
            }
            return $this->jsonOk($model, '添加成功');
        } else {
            $message = $model->errors[0] ?? '位置错误';
            return $this->jsonErr([], '添加失败！' . $message);
        }
    }

    public function detail(Request $request)
    {
        $id = $request->get('id', 0);
        $model = Product::query()
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->with('relateDetail')
            ->first();
        if ($model == null) {
            $message = "未找到此产品";
            return $this->jsonErr(80000, $message);
        } else {
            return $this->jsonOk($model, '');
        }
    }

    public function update(Request $request)
    {
        $id = $request->get('id');
        $model = Product::query()->where('id', $id)->with('relateDetail')->first();
        if ($model == null) {
            return $this->jsonErr([], '未找到此产品，id:' . $id);
        }
        $model->fill($request->all());
        if (!$model->save()) {
            throw new \RuntimeException("更新失败");
        }

        $detailModel = ProductDetail::query()->where('product_id', $id)
            ->first();
        $detailModel->fill($request->all());
        if (!$detailModel->save()) {
            throw new \RuntimeException("更新失败");
        }

        return $this->jsonOk($model, '更新成功');
    }

    public function delete()
    {

    }


}
