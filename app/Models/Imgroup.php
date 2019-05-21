<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class ImGroup extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'im_group';

    protected $rules = [
        'name' => 'required|unique:im_group|max:50',
        'avatar' => 'sometimes|required|image|max:500',
        'cover' => 'sometimes|required|image|mimes:jpg,png,gif|max:500'
    ];
    protected $message = [];
    protected $fillable = ['name', 'avatar', 'cover', 'type'];

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


}
