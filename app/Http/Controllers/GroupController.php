<?php

namespace App\Http\Controllers;

use App\Models\ImGroup;
use App\Models\Category;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(){

        $column = ['id', 'name', 'avatar', 'cover', 'creator', 'type', 'created_at', 'updated_at'];
        $result = ImGroup::query()->where('is_deleted', 0)
            ->limit(3)->offset(1)->get($column)->toArray();

        return $this->jsonOk($result);
    }

    public function create(Request $request)
    {
        $model = new ImGroup($request->all());

        if ($model->validate($request->all())) {
            if($file = $request->file('avatar')) {
                $prefix = '/opt/data/chongliao/';
                $path = 'group/avatar' . date('/Y/m/d/His/');
                $name = $model->name . $file->getClientOriginalExtension();
                $fullPath = $file->storeAs($prefix . $path . $name, $name);
                if($fullPath){
                    $model->avatar = $path;
                }
            }

            if($file = $request->file('cover')) {
                $prefix = '/opt/data/chongliao/';
                $path = 'group/cover' . date('/Y/m/d/His/');
                $name = $model->name . $file->getClientOriginalExtension();
                $fullPath = $file->storeAs($prefix . $path . $name, $name);
                if($fullPath){
                    $model->avatar = $path;
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
}
