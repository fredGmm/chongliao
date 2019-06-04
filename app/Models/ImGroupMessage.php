<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class ImGroupMessage extends Model
{
    use ValidatesRequests;

    protected $table = 'im_group_message';

    protected $rules = [
//        'name' => 'required|unique:im_group|max:50',
//        'avatar' => 'sometimes|required|image|max:500',
//        'cover' => 'sometimes|required|image|mimes:jpg,png,gif|max:500'
    ];
    protected $message = [];
    protected $fillable = ['relate_id', 'user_id', 'answer_id', 'content', 'status'];

    public $errors;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

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
