<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProductDetail
 * @package App\Models
 *
 */
class ProductDetail extends Model
{
    use ValidatesRequests;
    protected $table = 'product_detail';

    protected $rules = [
        'product_id' => 'required|unique:product_detail',
//        'introduction'=> 'digits_between:50,10000',
        'web_addr' => 'url'
    ];

    protected $message = [
        'product_id.unique' => '该产品已存在详情',
        'introduction.digits_between' => '介绍字数需要在50~10000字',
        'web_addr.url' => '官网地址必须是个URL地址'
    ];

    protected $fillable = ["product_id", "stroy", "introdution", "creator_id", "logo", "web_addr"];

    protected $defaults = [
        'introduction' => '',
        'logo' => '',
    ];

    public $errors;

    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes($this->defaults, true);
        parent::__construct($attributes);
    }

    public function validate($data)
    {
        $v = Validator::make($data, $this->rules, $this->message);
        if ($v->fails()) {
            $this->errors[] = $v->errors()->first();
            return false;
        }
        return true;
    }

}
