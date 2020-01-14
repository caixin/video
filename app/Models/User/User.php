<?php

namespace Models\User;

use Models\Model;

class User extends Model
{
    protected $table = 'user';

    protected $fillable = [
        'token',
        'username',
        'password',
        'email',
        'money',
        'referrer',
        'verify_code',
        'status',
        'remark',
        'create_ip',
        'create_ip_info',
        'create_ua',
        'login_ip',
        'login_time',
        'active_time',
        'free_time',
        'created_by',
        'updated_by',
    ];

    const STATUS = [
        0 => '发送简讯未注册',
        1 => '正常用户',
        2 => '封锁用户',
    ];

    public function share_users()
    {
        return $this->hasMany('Models\User\User', 'referrer');
    }

    public function referrer_user()
    {
        return $this->belongsTo('Models\User\User', 'referrer');
    }

    public function loginLog()
    {
        return $this->hasMany('Models\User\UserLoginLog', 'uid');
    }
}
