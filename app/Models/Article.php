<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class Article extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'tb_article';
    protected $connection = 'mysql_dn';
    protected $rules = [

    ];
    protected $message = [

    ];

    public $timestamps = FALSE;
    protected $fillable = ['title', 'content', 'class_id'];
    protected $appends = ['className'];
//    protected $hidden = ['path'];

    public $errors;

    public function validate($data)
    {
        return true;
    }

    public function getClassNameAttribute()
    {
        if($this->class_id) {
            $model = NoticeClass::query()
                ->where('id', $this->class_id)
                ->first();

            return $model->name ?? '';
        }
        return '';
    }

    public function setCoverAttribute($value)
    {
//        $this->attributes['cover'] = config('app.asset_url') .'static/' .$value;;
        if(strrpos($value, "http") === false) {
            $this->attributes['cover'] = config('app.asset_url')  .$value;
        }else{
            $this->attributes['cover'] = $value;
        }

    }



}
