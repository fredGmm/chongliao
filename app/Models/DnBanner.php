<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class DnBanner extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'tb_banner';
    protected $connection = 'mysql_dn';
    protected $rules = [

    ];
    protected $message = [

    ];

    public $timestamps = FALSE;
    protected $fillable = ['photo', 'type', 'zt', 'sort', 'url'];
    protected $appends = ['photoUrl'];
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

    public function setPhotoAttribute($value)
    {
        if(strrpos($value, "http") === false) {
            $this->attributes['photo'] = config('app.asset_url')  . $value;
        }else{
            $this->attributes['photo'] = $value;
        }
    }

   public function getPhotoUrlAttribute(){
        return config('app.asset_url') . $this->photo;
   }


    public function getStatusTextAttribute()
    {
        if($this->status == 0 ){
            $text = '进行中';
        }else{
            $text = '已完成';
        }
        return $text;
    }


}
