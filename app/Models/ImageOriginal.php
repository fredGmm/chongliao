<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

/**
 * Class Image
 * @package App\Models
 * @property integer id
 * @property integer image_id
 * @property string source
 * @property integer $is_deleted
 * @property string extra
 * @property string $created_at
 * @property string $updated_at
 */
class ImageOriginal extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'image_original';

    protected $rules = [
        'url' => 'required|unique:image_original',
    ];
    protected $message = [];
    protected $fillable = ['id', 'image_id', 'url', 'source', 'is_deleted', 'extra'];

    protected $appends = [];
    protected $hidden = [];

    public $errors;

    public function validate($data, $rules = [])
    {
        // make a new validator object
        $v = Validator::make($data, $rules ? $rules : $this->rules);

        // check for failure
        if ($v->fails()) {
            // set errors and return false
            $this->errors[] = $v->errors()->first();
            return false;
        }
        return true;
    }

    public static function add($data) {

        $model = new ImageOriginal($data);
        if ($model->validate($data)) {
            if (!$model->save()) {
                $message = $model->errors[0] ?? "未知错误";
//                throw new \RuntimeException("image_original 保存插入失败");
                echo $message;exit;
                return false;
            }
            return $model;
        } else {
            $message = $model->errors[0] ?? "未知错误";
            Log::error($message);
            echo $message;exit;
            return false;
        }
    }

}
