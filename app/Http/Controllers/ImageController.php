<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function index(Request $request) {
        $page = $request->get('page');
        $pageSize = $request->get("pageSize", 6);
        $category = '';
        // https://cswd.oss-cn-hangzhou.aliyuncs.com/recommend/Jyau6WKb5c498fa97d862.jpg

        $page = $request->get('page', 1);
        $offset = ($page - 1) * $pageSize;
        $pageSize = $request->get('pageSize', 9);
        $query =  Image::query()->where('is_deleted' , 0);
        $images = $query->offset($offset)->limit($pageSize)->orderBy('id', 'desc')->get();
        $count = $query->count();

        return $this->jsonOk(['list' => $images, 'count' => $count]);
    }

    public function create(Request $request)
    {
        $files = $request->file();

        foreach ($files as $title =>$file) {
            $prefix = 'chongliao';
            $path =  date('/Y/m/d/H');
            $name = uniqid() . '.' .$file->getClientOriginalExtension();
            $fullPath = $file->storeAs($prefix . $path , $name);
            $model = new Image();
            $model->category_id = 0;
            $model->title = $title;
            $model->path = $fullPath;
            $model->is_deleted = 0;
            $model->save();
        }
        return $this->jsonOk([], '添加成功');
    }


}
