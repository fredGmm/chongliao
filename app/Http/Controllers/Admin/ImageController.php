<?php
/**
 * Created by PhpStorm.
 * User: fredgui
 * Date: 2019/12/28
 * Time: 11:37
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function index(Request $request)
    {
        $categoryId = $request->get('category_id', 0);
        $status = $request->get('status', 0);
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 9);
        $offset = ($page - 1) * $pageSize;
        /** @var Image $query */
        $query = Image::query()->where('is_deleted', 0)
            ->where('status', $status);

        $images = $query->category($categoryId)->offset($offset)->limit($pageSize)
//            ->orderBy('category_id', 'asc')
            ->orderBy('id', 'desc')->get();

        $count = $query->count();
        return $this->jsonOk(['list' => $images, 'count' => $count]);
    }

    public function update(Request $request) {
        $id = $request->get('id');
        $model = Image::query()->where('id', $id)->where('status', 1)
            ->first();

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
