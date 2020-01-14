<?php

namespace Models\Pmtools;

use Models\Model;

class DailyRetention extends Model
{
    const UPDATED_AT = null;

    protected $table = 'daily_retention';

    protected $fillable = [
        'date',
        'type',
        'all_count',
        'day_count',
        'avg_money',
    ];

    const TYPE = [
        1 => '1日內有登入',
        2 => '3日內有登入',
        3 => '7日內有登入',
        4 => '15日內有登入',
        5 => '30日內有登入',
        6 => '31日以上未登入',
    ];

    const TYPE_LIGHT = [
        1 => '绿灯(Green)',
        2 => '黄灯(Yellow)',
        3 => '蓝灯(Blue)',
        4 => '橘灯(Orange)',
        5 => '红灯(Red)',
        6 => '灰灯(Gray)',
    ];

    const TYPE_COLOR = [
        1 => 'green',
        2 => 'yellow',
        3 => 'blue',
        4 => 'orange',
        5 => 'red',
        6 => 'gray',
    ];

    const ANALYSIS_TYPE = [
        1 => '创帐号后过1日以上有登入',
        2 => '创帐号后过3日以上有登入',
        3 => '创帐号后过7日以上有登入',
        4 => '创帐号后过15日以上有登入',
        5 => '创帐号后过30日以上有登入',
        6 => '创帐号后过60日以上有登入',
        7 => '创帐号后过90日以上有登入',
        8 => '创帐号后在今天往前7日内还有登入',
    ];
}
