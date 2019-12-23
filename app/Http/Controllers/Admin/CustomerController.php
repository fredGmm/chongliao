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
