<?php

namespace Models\System;

use Models\Model;

class Sysconfig extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $table = 'sysconfig';

    protected $attributes = [
        'sort' => 1,
    ];

    protected $fillable = [
        'skey',
        'svalue',
        'info',
        'groupid',
        'type',
        'sort',
    ];

    const GROUPID = [
        1 => '站点设置',
        2 => '維護设置',
    ];
    
    const TYPE = [
        1 => '字串(text)',
        2 => '数字(number)',
        3 => '文本域(textarea)',
        4 => '布林值(Y/N)',
    ];
}
