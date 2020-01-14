<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

/**
 * Class Image
 * @package App\Models
 * @property integer $category_id
 * @property string $title
 * @property string $path
 * @property integer $is_deleted
 * @property integer $status
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
        'path' => 'required|max:500',
        'title' => 'required|max:255',
        'category_id' => 'max:4',
        'source_url' => 'sometimes',
    ];
    protected $message = [];
    protected $fillable = ['category_id', 'related_id','title', 'path', 'status', 'is_deleted', 'source_url'];

    protected $appends = ['categoryName', 'url','statusText'];
    protected $hidden = ['path'];

    public $errors;

    public function validate($data, $rules = [])
    {
//        $this->rules['related_id'] =  Rule::unique('image')->where(function ($query) {
//            $query->where('related_id', '>', 0);
//        });
        // make a new validator object
        $v = Validator::make($data, $rules ?$rules : $this->rules);

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

    public function getStatusTextAttribute(){
        $map = [
            '0' => 'default',
            '-1' => 'no',
            '1' => 'yes'
        ];
        return $map[$this->status] ?? 'default';
    }
}
