<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{


    public function create(Request $request) {
//        var_dump($request->all());exit;
        $model = new ProductDetail($request->all());
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
}
