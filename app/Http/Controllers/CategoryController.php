<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $column = ['id', 'name', 'updated_at'];
        $result = Category::query()->where('is_deleted', 0)->get($column)->toArray();

        return $this->jsonOk($result);
    }

    public function create(Request $request)
    {
        $model = new Category();
        if ($model->validate($request->all())) {
            $name = $request->post('name');
            $model->name = $name;
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

}
