<?php

namespace App\Models;

use App\Http\Controllers\ImageController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * App\Models\ImGroup
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImGroup query()
 * @mixin \Eloquent
 */
class ImGroup extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'im_group';

    protected $rules = [
        'name' => 'required|unique:im_group|max:50',
        'avatar' => 'sometimes|required|image',
        'cover' => 'sometimes|required|image|mimes:jpg,png,gif'
    ];
    protected $message = [];
    protected $fillable = ['name', 'avatar', 'cover', 'type'];
    protected $hidden = ['created_at', 'avatar', 'updated_at'];
    protected $appends = ['time', 'groupAvatar', 'unread', 'message'];

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

    public function relateImGroupMessage()
    {
        return $this->hasMany('\App\Models\ImGroupMessage', 'group_id', 'id');
    }

    public function getTimeAttribute()
    {
        return strtotime($this->attributes['created_at']);
    }

    public function getGroupAvatarAttribute()
    {
        return config('app.asset_url') . $this->attributes['avatar'];
    }

    public function getUnreadAttribute()
    {
        $count = ImGroupMessage::query()->where('group_id', $this->id)
            ->where('status', 0)
            ->where('is_deleted', 0)
            ->count();
        return $count;
    }

    public function getMessageAttribute()
    {
        $model = ImGroupMessage::query()->where('group_id', $this->id)
            ->where('status', 0)
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->orderBy('created_at', 'desc')
            ->get(['content'])
            ->first();
        $user = Auth::user();
        $isSendMsg = ImGroupMessage::query()->where('user_id',$user->id)
            ->where('group_id', $this->id)->exists();
        return empty($model->content) && empty($isSendMsg) ? '欢迎宠聊聊宠团！(￣▽￣)~*' :  $model->content;
    }

}
