<?php

namespace App\Models;

use App\Helpers\OSS;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Psy\Util\Json;
use Seld\JsonLint\JsonParser;

/**
 * Class Image
 * @package App\Models
 * @property integer $category_id
 * @property string $title
 * @property string $path
 * @property string $extra
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
    protected $fillable = ['category_id', 'related_id', 'title', 'path', 'status', 'is_deleted', 'source_url'];

    protected $appends = ['categoryName', 'url', 'statusText', 'preUrl'];
    protected $hidden = ['path'];

    public $errors;

    public function validate($data, $rules = [])
    {
//        $this->rules['related_id'] =  Rule::unique('image')->where(function ($query) {
//            $query->where('related_id', '>', 0);
//        });
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

    public function getCategoryNameAttribute()
    {
        return '';
    }

    public function getUrlAttribute()
    {
        $url = config('app.asset_url') . $this->path;
        $ossUrl = "https://chongliao-oss.oss-cn-beijing.aliyuncs.com/" . $this->path  . "?x-oss-process=image/resize,m_fill,h_160,w_160";
        return $this->status == 1 ? $ossUrl : $url;
    }

    public function getPreUrlAttribute()
    {
        $url = config('app.asset_url') . $this->path;
        $ossUrl = "https://chongliao-oss.oss-cn-beijing.aliyuncs.com/" . $this->path ;
        return $this->status == 1 ? $ossUrl : $url;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $categoryId
     * @return mixed
     */
    public function scopeCategory($query, $categoryId)
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function getStatusTextAttribute()
    {
        $map = [
            '0' => 'default',
            '-1' => 'no',
            '1' => 'yes'
        ];
        return $map[$this->status] ?? 'default';
    }

    public function changeImagePath()
    {
        if ((int)$this->status == 1) {
            $fullpath = storage_path("app/") . $this->path;
            //上传到 oss
            $name = "chongliao-{$this->id}"; // $file->getClientOriginalExtension()
            $path = date('Y/m/d/') . $name . '.' . pathinfo($fullpath, PATHINFO_EXTENSION);

            try {
                $result = OSS::privateUpload("chongliao-oss", $path, $fullpath,
                    ['ContentType' => $this->getMime()]);

                if ($result) {
                    $this->is_deleted = 0;
                    // ?x-oss-process=image/resize,m_lfit,h_160,w_160
                    // ?x-oss-process=image/resize,m_fill,h_160,w_160
                    $this->path = $path;
                    if (!$this->save()) {
                        \Log::error("上传图片至oss异常");
                    }
                }
            } catch (\Exception $e) {
                \Log::error("上传图片至oss异常:" . $e->getMessage());
            }
        }
    }

    public function getMime()
    {
        try {
            if ($this->extra) {
                $extraData = json_decode($this->extra, true);
                if (!empty($extraData['mime'])) {
                    return $extraData['mime'];
                }
            }
        } catch (\Exception $e) {
            \Log::error("获取mime错误:" . $e->getMessage());
        }
        return mime_content_type(storage_path("app/") . $this->path);
    }
}
