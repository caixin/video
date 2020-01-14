<?php

namespace Models\Pmtools;

use Models\Model;

class DailyRetentionUser extends Model
{
    const UPDATED_AT = null;

    protected $table = 'daily_retention_user';

    protected $fillable = [
        'date',
        'type',
        'all_count',
        'day_count',
        'percent',
    ];

    const TYPE = [
        1 => '1天前新用户',
        2 => '3天前新用户',
        3 => '7天前新用户',
        4 => '15天前新用户',
        5 => '30天前新用户',
    ];
}
