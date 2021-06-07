<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class DnUserClass extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'tb_user_class';
    protected $connection = 'mysql_dn';
    protected $rules = [

    ];
    protected $message = [

    ];

    public $timestamps = FALSE;
    protected $fillable = [
        'name'
    ];
    protected $appends = [];
//    protected $hidden = ['path'];

    public $errors;

    public function validate($data)
    {
        $this->create_time = date('Y-m-d H:i:s');
        return true;
    }

//    public function setIdCardAttribute($value)
//    {
//
//    }
//
//    public function getClassNameAttribute()
//    {
////        if($this->class_id) {
////            $model = NoticeClass::query()
////                ->where('id', $this->class_id)
////                ->first();
////
////            return $model->name ?? '';
////        }
////        return '';
//    }


}
