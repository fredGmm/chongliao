<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class DnClockMainClass extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'tb_clock_main_class';
    protected $connection = 'mysql_dn';
    protected $rules = [

    ];
    protected $message = [

    ];

    public $timestamps = FALSE;
    protected $fillable = ['name'];
    protected $appends = [];
//    protected $hidden = ['path'];

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

//    public function setPathAttribute()
//    {
//    }




}
