<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class Image extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'image';

    protected $rules = [
//        'name' => 'required|unique:im_group|max:50',
//        'avatar' => 'sometimes|required|image|max:500',
//        'cover' => 'sometimes|required|image|mimes:jpg,png,gif|max:500'
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

    public function getCategoryNameAttribute(){

        return 'aa';
    }

    public function getUrlAttribute()
    {
        return 'http://chongliao.me/' . $this->path;
    }
}
