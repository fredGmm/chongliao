<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * App\Models\ImGroupMember
 * @property $group_id int
 * @property $user_id int
 * @property $is_deleted int
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
        'group_id' => 'required|exists:im_group,id,is_deleted,0|unique:im_group_member,group_id,null,null,user_id,{$user_id}',
        'user_id' => 'required|exists:user_info,id,is_deleted,0',
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
        if (ImGroupMember::isJoin($data['group_id'],$data['user_id'])) {
            $this->errors[] = "你已经加入该群组";
            return false;
        }
        return true;
    }

    public static function isJoin($group_id,$user_id)
    {
        $exist = ImGroupMember::where('group_id', $group_id)
            ->where('user_id', $user_id)
            ->where('is_deleted', 0)
            ->exists();
        return $exist;
    }

    public static function join($groupId, $userId) {

        if(!ImGroupMember::isJoin($groupId,$userId)){
            $model = new ImGroupMember();
            $model->group_id = $groupId;
            $model->user_id = $userId;
            $model->is_deleted = 0;
            return $model->save();
        }
        return true;
    }
}
