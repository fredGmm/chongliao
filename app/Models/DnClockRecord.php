<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class DnClockRecord extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'tb_clock_record';
    protected $connection = 'mysql_dn';
    protected $rules = [

    ];
    protected $message = [

    ];

    public $timestamps = FALSE;
    protected $fillable = [];
    protected $appends = ['mainClassName','className','communityName','userName','userPhone'];
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

    public function getUserNameAttribute()
    {
        $model = DnUser::query()
            ->where('id', $this->main_class_id)
            ->first();

        return $model->real_name ?? '未知';
    }

    public function getUserPhoneAttribute()
    {
        $model = DnUser::query()
            ->where('id', $this->main_class_id)
            ->first();

        return $model->phone ?? '未知';
    }

    public function getMainClassNameAttribute()
    {
        $model = DnClockMainClass::query()
            ->where('id', $this->main_class_id)
            ->first();

        return $model->name ?? '未知';
    }

    public function getClassNameAttribute()
    {
        $model = DnClockClass::query()
            ->where('id', $this->class_id)
            ->first();

        return $model->name ?? '未知';
    }

    public function getCommunityNameAttribute()
    {
        $model = DnUserCommunity::query()
            ->where('id', $this->community_id)
            ->first();

        return $model->name ?? '未知';
    }




}
