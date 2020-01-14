<?php

namespace Models\User;

use Models\Model;

class UserMoneyLog extends Model
{
    const UPDATED_AT = null;
    
    protected $table = 'user_money_log';

    protected $fillable = [
        'uid',
        'type',
        'video_keyword',
        'money_before',
        'money_add',
        'money_after',
        'description',
    ];

    const TYPE = [
        0 => '用户注册加点',
        1 => '推荐人加点',
        2 => '观看视频扣点',
        3 => '每日签到加点',
        4 => '人工加点',
        5 => '人工扣点',
    ];

    public function user()
    {
        return $this->belongsTo('Models\User\User', 'uid');
    }

    public function video()
    {
        return $this->belongsTo('Models\Video\Video', 'video_keyword', 'keyword');
    }
}
