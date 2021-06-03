<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class DnClockThreeClass extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'tb_clock_three_class';
    protected $connection = 'mysql_dn';
    protected $rules = [

    ];
    protected $message = [

    ];

    public $timestamps = FALSE;
    protected $fillable = ['class_id','name'];
    protected $appends = ['className'];
    protected $hidden = [];

    public $errors;

    public function validate($data)
    {
        // make a new validator object
//        $v = Validator::make($data, $this->rules, $this->message);
//
//        // check for failure
//        if ($v->fails()) {
//            // set errors and return false
//            $this->errors[] = $v->errors()->first();
//            return false;
//        }
        return true;
    }

    public function getClassNameAttribute()
    {
        $model = DnClockClass::query()
            ->where('id', $this->class_id)
            ->first();

        return $model->name ?? '未知';
    }




}
