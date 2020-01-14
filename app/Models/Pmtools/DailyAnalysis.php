<?php

namespace Models\Pmtools;

use Models\Model;

class DailyAnalysis extends Model
{
    const UPDATED_AT = null;

    protected $table = 'daily_analysis';

    protected $fillable = [
        'date',
        'type',
        'count',
    ];

    const TYPE = [
        1 => '每日添加用户数(NUU)',
        2 => '每日不重复登入用户数(DAU)',
        3 => '每周不重复登入用户数(WAU)',
        4 => '每月不重复登入用户数(MAU)',
        5 => 'DAU - NUU',
        6 => '最大同时在线用户数(PCU)',
    ];
}
