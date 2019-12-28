<?php

namespace App\Http\Controllers;

use App\Helpers\OSS;
use App\Models\Image;
use App\Models\ImGroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{

    public function index(Request $request)
    {
        $categoryId = $request->get('category_id', 0);

        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 9);
        $offset = ($page - 1) * $pageSize;
        /** @var Image $query */
        $query = Image::query()->where('is_deleted', 0)
            ->where('status', 1);

        $images = $query->category($categoryId)->offset($offset)->limit($pageSize)
//            ->orderBy('category_id', 'asc')
            ->orderBy('id', 'desc')->get();

        $count = $query->count();
        return $this->jsonOk(['list' => $images, 'count' => $count]);
    }

    /**
     * 爬虫 得到的图上，通过这个接口入库
     * @param Request $request
     * @return $this
     */
    public function create(Request $request)
    {
        $type = $request->get('prefix', 'chongliao');
        $categoryId = $request->get('category_id', '0');
        $files = $request->file();
        $data = [];
        Log::info($files);
        $prefix = $type;
        $path = date('/Y/m/d/H');
        foreach ($files as $title => $file) {
            $name = uniqid() . '.' . $file->getClientOriginalExtension();
            $fullPath = $file->storeAs($prefix . $path, $name);
            $params = [
                'category_id' => $categoryId,
                'title' => $title,
                'path' => $fullPath,
                'is_deleted' => 0
            ];
            $model = new Image($params);
            if ($model->validate($params)) {
                if (!$model->save()) {
                    throw new \RuntimeException("保存插入失败");
                }
                $data[] = $model;
            } else {
                $message = $model->errors[0] ?? "未知错误";
                Log::error($message);
                return $this->jsonOk([], '加入失败！' . $message);
            }
        }
        return $this->jsonOk($data, '添加成功');
    }

    /**
     * 上传图片到 到 oss
     * @param Request $request
     * @return $this
     */
    public function upload(Request $request)
    {
        $files = $request->file();
        $data = [];
        foreach ($files as $title => $file) {
            $model = new Image();
            $model->category_id = 0;
            $model->title = $title;
            $model->path = "";
            $model->is_deleted = 1;
            $model->save();
            $name = "chongliao-{$model->id}-{$title}"; // $file->getClientOriginalExtension()
            $path = "index/" . $name;
            try {
                $result = OSS::publicUpload("chongliao", $path, $file->path(),
                    ['ContentType' => $file->getMimeType()]);
                if ($result) {
                    $model->is_deleted = 0;
                    $model->path = $path . "?x-oss-process=image/resize,m_fixed,h_160,w_160";
                    $model->save();
                    $data[] = $model->getOssUrlAttribute();
                }
            } catch (\Exception $e) {
                \Log::error("上传图片至oss异常:" . $e->getMessage());
            }
        }
        return $this->jsonOk($data, '上传成功');
    }
}
