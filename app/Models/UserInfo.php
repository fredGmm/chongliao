<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\UserInfo
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInfo query()
 * @mixin \Eloquent
 */
class UserInfo extends Authenticatable
{
    use Notifiable;

    const DefaultAvatar = 'https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png';
    protected $table = 'user_info';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'nick_name', 'gender', 'phone','password', 'email','api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function getRandom($limit) {

        $userModels = UserInfo::query()->where('is_deleted', 0)
            ->orderByRaw('rand()')
            ->limit($limit)
            ->get(['id', 'name', 'avatar']);

        return $userModels;

    }

}
