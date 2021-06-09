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
    public function options()
    {
        return $this->jsonOk([]);
    }
    public function index(Request $request)
    {
        $categoryId = $request->get('category_id', 0);
//        $status = $request->get('status', 0);
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 20);
        $offset = ($page - 1) * $pageSize;

        /** @var Image $query */
        $query = Image::query()->where('is_deleted', 0)
            ->where('status', '0');
//            ->where(function ($query) use ($status) {
//                if($status != null) {
//                    $query->where('status', '>',$status);
//                }
//            });

        $count = $query->count();
        $images = $query->category($categoryId)->offset($offset)->limit($pageSize)
//            ->orderBy('category_id', 'asc')
            ->orderBy('id', 'desc')->get();


        return $this->jsonOk(['list' => $images, 'total' => $count]);
    }

    public function update(Request $request) {
        $id = $request->get('id');
        $model = Image::query()->where('id', $id)
            ->first();
        if($model == null){
            return $this->jsonErr([], '未找到此条目！');
        }

        $model->fill($request->all());
        $rule = [
            'status' => 'int|in:0,1,-1',
        ];
        if ($model->validate($request->all(), $rule)) {
            // 验证组是否存在
            $model->updated_at = date('Y-m-d H:i:s');
            $status = $model->save();
            if (!$status) {
                throw new \RuntimeException("更新失败");
            }
            $model->changeImagePath();
            return $this->jsonOk($model, '更新成功！');
        } else {
            $message = $model->errors[0] ?? '未知错误';
            return $this->jsonOk([], '加入失败！' . $message);
        }
    }


    public function create(Request $request)
    {

        $files = $request->file();
        $data = [];
        \Log::info($files);
        $prefix = "dn";
        $path = "/{$prefix}" . date('/Y/m/d/H');
        foreach ($files as $key => $file) {
            $name = uniqid() . '.' . $file->getClientOriginalExtension();
            $fullPath = $file->storeAs($path, $name);
//            move_uploaded_file(storage_path('app').'/'.$fullPath.'/'.$name,"/data/html/static/{$name}");
            @copy(storage_path('app').'/'.$fullPath,"/data/html/static/{$name}");
            return $this->jsonOk(config('app.asset_url') .$name, '添加成功');
        }
        return $this->jsonOk($data, '添加成功');
    }
}
