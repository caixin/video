<?php

namespace Models\Pmtools;

use Models\Model;

class ConcurrentUser extends Model
{
    const UPDATED_AT = null;

    protected $table = 'concurrent_user';

    protected $fillable = [
        'per',
        'minute_time',
        'count',
    ];

    const PER = [
        1 => '每分钟',
        5 => '每5分钟',
        10 => '每10分钟',
        30 => '每30分钟',
        60 => '每小时',
    ];
}
