<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

/**
 * App\Models\ImGroupMember
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImGroupMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImGroupMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImGroupMember query()
 * @mixin \Eloquent
 */
class ImGroupMember extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'im_group_member';

    protected $rules = [
//        'name' => 'required|unique:im_group|max:50',
//        'avatar' => 'sometimes|required|image|max:500',
//        'cover' => 'sometimes|required|image|mimes:jpg,png,gif|max:500'
    ];
    protected $message = [];
    protected $fillable = ['group_id', 'user_id'];

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
