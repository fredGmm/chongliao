<?php

namespace App\Http\Controllers\Dn;

use App\Http\Controllers\Controller;

use App\Models\Activity;
use App\Models\Article;
use App\Models\DnBanner;
use App\Models\DnClockClass;
use App\Models\DnClockMainClass;
use App\Models\DnClockRecord;
use App\Models\DnClockRecordClass;
use App\Models\DnUser;
use App\Models\DnUserCommunity;
use Illuminate\Http\Request;

class DnController extends Controller
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


    public function activity_list(Request $request)
    {

        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;

        //处理排序
        $order = $request->get('sort', '-create_time');
        $desc = strrpos('-', $order) === false ? 'asc' : 'desc';

        //筛选
        $type = $request->get('type', '');
        $query = Activity::query();
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
        if($detailModel) {
            $detailModel->fill($request->all());
            if (!$detailModel->save()) {
                throw new \RuntimeException("更新失败");
            }
        }


        return $this->jsonOk($model, '更新成功');
    }

    public function delete()
    {

    }

    public function article_list(Request $request){
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;
        //处理排序
        $order = $request->get('sort', '-create_time');
        $desc = strrpos('-', $order) === false ? 'asc' : 'desc';

        //筛选
        $type = $request->get('type', '');
        $query = Article::query();
        if ($type) {
            $query->where('type', $type);
        }
        $list = $query->offset($offset)->limit($pageSize)->orderBy(ltrim($order, '-'), $desc)
            ->get();
        $count = $query->count();

        return $this->jsonOk(['list' => $list, 'total' => $count]);
    }

    public function article_create(Request $request) {
        $model = new Article($request->all());

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

    public function article_detail(Request $request)
    {
        $id = $request->get('id', 0);
        $model = Article::query()
            ->where('id', $id)
            ->first();
        if ($model == null) {
            $message = "未找到此文章";
            return $this->jsonErr(80000, $message);
        } else {
            return $this->jsonOk($model, '');
        }
    }

    public function article_update(Request $request)
    {
        $id = $request->get('id');
        $model = Article::query()->where('id', $id)->first();
        if ($model == null) {
            return $this->jsonErr([], '未找到此文章，id:' . $id);
        }
        $model->fill($request->all());
        if (!$model->save()) {
            throw new \RuntimeException("更新失败");
        }

//        $detailModel = ProductDetail::query()->where('product_id', $id)
//            ->first();
//        if($detailModel) {
//            $detailModel->fill($request->all());
//            if (!$detailModel->save()) {
//                throw new \RuntimeException("更新失败");
//            }
//        }


        return $this->jsonOk($model, '更新成功');
    }

    public function user_list(Request $request){
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;
        //处理排序
        $order = $request->get('sort', '-create_time');
        $desc = strrpos('-', $order) === false ? 'asc' : 'desc';

        //筛选
        $type = $request->get('type', '');
        $query = DnUser::query();
        if ($type) {
            $query->where('type', $type);
        }
        $list = $query->offset($offset)->limit($pageSize)->orderBy(ltrim($order, '-'), $desc)
            ->get();
        $count = $query->count();

        return $this->jsonOk(['list' => $list, 'total' => $count]);
    }

    public function user_create(Request $request) {
        $model = new DnUser($request->all());

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

    public function user_detail(Request $request)
    {
        $id = $request->get('id', 0);
        $model = DnUser::query()
            ->where('id', $id)
            ->first();
        if ($model == null) {
            $message = "未找到此文章";
            return $this->jsonErr(80000, $message);
        } else {
            return $this->jsonOk($model, '');
        }
    }

    public function user_update(Request $request)
    {
        $id = $request->get('id');
        $model = DnUser::query()->where('id', $id)->first();
        if ($model == null) {
            return $this->jsonErr([], '未找到此用户，id:' . $id);
        }
        $model->fill($request->all());
        if (!$model->save()) {
            throw new \RuntimeException("更新失败");
        }
        return $this->jsonOk($model, '更新成功');
    }

    public function community_list(Request $request) {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;
        //处理排序

        $query = DnUserCommunity::query();

        $list = $query->offset($offset)->limit($pageSize)
            ->get();
        $count = $query->count();

        return $this->jsonOk(['list' => $list, 'total' => $count]);
    }

    public function banner_update(Request $request)
    {
        $id = $request->get('id');
        $model = DnBanner::query()->where('id', $id)->first();
        if ($model == null) {
            return $this->jsonErr([], '未找到此用户，id:' . $id);
        }
        $model->fill($request->all());
        $model->photo = $request->imageUrl;
        if (!$model->save()) {
            throw new \RuntimeException("更新失败");
        }
        return $this->jsonOk($model, '更新成功');
    }

    public function banner_list(Request $request) {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;
        //处理排序

        $query = DnBanner::query();

        $list = $query->offset($offset)->limit($pageSize)
            ->get();
        $count = $query->count();

        return $this->jsonOk(['list' => $list, 'total' => $count]);
    }

    public function banner_create(Request $request)
    {

        $model = new DnBanner($request->all());

        if ($model->validate($request->all())){
            $model->photo = $request->imageUrl;
            if (!$model->save()) {
                throw new \RuntimeException("插入失败");
            }
            return $this->jsonOk($model, '添加成功');
        } else{
            $message = $model->errors[0] ?? '位置错误';
            return $this->jsonErr([], '添加失败！' . $message);
        }
    }

    public function banner_detail(Request $request)
    {
        $id = $request->get('id', 0);
        $model = DnBanner::query()
            ->where('id', $id)
            ->first();
        if ($model == null) {
            $message = "未找到此文章";
            return $this->jsonErr(80000, $message);
        } else {
            return $this->jsonOk($model, '');
        }
    }

    public function clock_main_class(Request $request) {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;
        //处理排序

        $query = DnClockMainClass::query();

        $list = $query->offset($offset)->limit($pageSize)
            ->get();
        $count = $query->count();

        return $this->jsonOk(['list' => $list, 'total' => $count]);
    }

    public function clock_main_class_detail(Request $request)
    {
        $id = $request->get('id', 0);
        $model = DnClockMainClass::query()
            ->where('id', $id)
            ->first();
        if ($model == null) {
            $message = "未找到此文章";
            return $this->jsonErr(80000, $message);
        } else {
            return $this->jsonOk($model, '');
        }
    }

    public function clock_main_class_create(Request $request)
    {

        $model = new DnClockMainClass($request->all());

        if ($model->validate($request->all())){

            if (!$model->save()) {
                throw new \RuntimeException("插入失败");
            }
            return $this->jsonOk($model, '添加成功');
        } else{
            $message = $model->errors[0] ?? '位置错误';
            return $this->jsonErr([], '添加失败！' . $message);
        }
    }


    public function clock_main_class_update(Request $request)
    {
        $id = $request->get('id');
        $model = DnClockMainClass::query()->where('id', $id)->first();
        if ($model == null) {
            return $this->jsonErr([], '未找到此用户，id:' . $id);
        }
        $model->fill($request->all());
        if (!$model->save()) {
            throw new \RuntimeException("更新失败");
        }
        return $this->jsonOk($model, '更新成功');
    }

    public function clock_class(Request $request) {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;
        //处理排序

        $query = DnClockClass::query();

        $list = $query->offset($offset)->limit($pageSize)
            ->get();
        $count = $query->count();

        $main_class_map = DnClockMainClass::query()->get();
//        var_dump($main_class_map);exit;
        return $this->jsonOk(['list' => $list, 'total' => $count, 'main_class_map' => $main_class_map]);
    }

    public function clock_class_create(Request $request)
    {

        $model = new DnClockClass($request->all());

        if ($model->validate($request->all())){

            if (!$model->save()) {
                throw new \RuntimeException("插入失败");
            }
            return $this->jsonOk($model, '添加成功');
        } else{
            $message = $model->errors[0] ?? '位置错误';
            return $this->jsonErr([], '添加失败！' . $message);
        }
    }

    public function clock_class_update(Request $request)
    {
        $id = $request->get('id');
        $model = DnClockClass::query()->where('id', $id)->first();
        if ($model == null) {
            return $this->jsonErr([], '未找到此用户，id:' . $id);
        }
        $model->fill($request->all());
        if (!$model->save()) {
            throw new \RuntimeException("更新失败");
        }
        return $this->jsonOk($model, '更新成功');
    }

    public function clock_records(Request $request) {
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);
        $offset = ($page - 1) * $pageSize;
        //处理排序

        $query = DnClockRecord::query();

        $list = $query->offset($offset)->limit($pageSize)
            ->get();
        $count = $query->count();

        $main_class_map = DnClockMainClass::query()->get();
//        var_dump($main_class_map);exit;
        return $this->jsonOk(['list' => $list, 'total' => $count, 'main_class_map' => $main_class_map]);
    }


}
