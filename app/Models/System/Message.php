<?php

namespace Models\System;

use Models\Model;

class Message extends Model
{
    protected $table = 'message';

    protected $fillable = [
        'uid',
        'type',
        'content',
        'created_by',
        'updated_by',
    ];

    const TYPE = [
        1 => '帐号问题',
        2 => '点数问题',
        3 => '播放问题',
        4 => '其他问题',
    ];

    public function user()
    {
        return $this->belongsTo('Models\User\User', 'uid');
    }
}
