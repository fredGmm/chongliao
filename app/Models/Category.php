<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

/**
 * Class Category
 * @property string $name
 * @package App\Models
 */
class Category extends Model
{
    use ValidatesRequests;

    protected $table = 'category';

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    // 数据验证
    protected $rules = [
        'name' => 'required|min:1|max:50|unique:category',  //必填 字符串
    ];

    protected $messages = [
        'name.unique' => '该分类名已存在!',
    ];

    public $errors;

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules, $this->messages);

        // check for failure
        if ($v->fails()) {
            // set errors and return false
            $this->errors[] = $v->errors()->first();
            return false;
        }
        return true;
    }

    public static function getGroups()
    {

    }

}

