<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class Product extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'product';

    protected $rules = [
        'name' => 'required|unique:product|max:255',
        'summary' => 'required|max:1000',
        'type' => 'in:APP,WEB,SRV,HARDWARE',
        'company' => 'required|max:255',
        'birthday' => 'date',
        'city' => 'max:100',
        'death_time' => 'required|date'
    ];
    protected $message = [
        'name.unique' => '已存在该产品',
        'summary.required' => '简介还是要来一波的',
        'type.in' => '正确指定产品类型',
        'company.required' => '公司名必填;个人产品，请填写个体',
        'birthday.date' => '出生日期格式错误',
        'death_time' => '死亡日期格式错误'
    ];

    protected $fillable = ['name', 'summary', 'type', 'company', 'birthday', 'death_time'];
//    protected $appends = ['categoryName', 'url'];
//    protected $hidden = ['path'];

    public $errors;

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules, $this->message);

        // check for failure
        if ($v->fails()) {
            // set errors and return false
            $this->errors[] = $v->errors()->first();
            return false;
        }
        return true;
    }

    public function setPathAttribute()
    {
    }

    public function relateDetail()
    {
        return $this->hasOne('\App\Models\ProductDetail', 'product_id', 'id');
    }
}
