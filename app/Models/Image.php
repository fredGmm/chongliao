<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Builder;

/**
 * Class Image
 * @package App\Models
 * @property integer $category_id
 * @property string $title
 * @property string $path
 * @property integer $is_deleted
 * @property string $created_at
 * @property string $updated_at
 * @method Builder category($cid)
 */
class Image extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'image';

    protected $rules = [
        'path' => 'required|image|max:500',
        'title' => 'sometimes|max:255'
    ];
    protected $message = [];
    protected $fillable = [];

    protected $appends = ['categoryName', 'url'];
    protected $hidden = ['path'];

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

    public function getCategoryNameAttribute()
    {
        return '';
    }

    public function getUrlAttribute()
    {
        return config('app.asset_url') . $this->path;
    }

    public function getOssUrlAttribute()
    {
        return "https://chongliao.oss-cn-hangzhou.aliyuncs.com/" . $this->path;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $categoryId
     * @return mixed
     */
    public function scopeCategory($query, $categoryId) {
        if($categoryId) {
            $query->where('category_id', $categoryId);
        }
        return $query;
    }
}
