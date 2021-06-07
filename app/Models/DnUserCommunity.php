<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class DnUserCommunity extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'tb_user_community';
    protected $connection = 'mysql_dn';
    protected $rules = [

    ];
    protected $message = [

    ];

    public $timestamps = FALSE;
    protected $fillable = ['class_id','name'];
    protected $appends = ['community_user'];
//    protected $hidden = ['path'];

    public $errors;

    public function validate($data)
    {
        return true;
    }

    public function getCommunityUserAttribute()
    {
        $name_array = [];
        if($this->id) {
            $model = DnUser::query()->select(['nick_name'])
                ->where('community_id',$this->id)
                ->get();
            if($model) {
                foreach ($model as $value) {
                    $name_array[] = ($value->nick_name);
                }
            }

            return $name_array ? implode('|', $name_array) : '';
        }
        return '';
    }

}
