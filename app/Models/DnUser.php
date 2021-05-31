<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Validator;

class DnUser extends Model
{
    use ValidatesRequests;
    //
    protected $table = 'tb_user';
    protected $connection = 'mysql_dn';
    protected $rules = [

    ];
    protected $message = [

    ];

    public $timestamps = FALSE;
    protected $fillable = ['real_name','nick_name','phone','password','id_card','certificated','sex','mail',
    'province','city','area','user_level','community_level','community_id','class_id','love_count','love_gold_coin',
   'continuous_clock_count','update_count'
    ];
    protected $appends = ['refereeName'];
//    protected $hidden = ['path'];

    public $errors;

    public function validate($data)
    {
        return true;
    }

    public function getClassNameAttribute()
    {
//        if($this->class_id) {
//            $model = NoticeClass::query()
//                ->where('id', $this->class_id)
//                ->first();
//
//            return $model->name ?? '';
//        }
//        return '';
    }

    public function getRefereeNameAttribute()
    {
        if($this->referee_id) {
            $model = DnUser::query()->select(['nick_name'])
                ->where('community_id',$this->referee_id)
                ->first();
            return $model->nick_name ?? '无';
        }
        return '无';
    }

}
