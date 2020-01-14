<?php

namespace Models\System;

use Models\Model;

class Ads extends Model
{
    protected $table = 'ads';

    protected $attributes = [
        'start_time' => '2019-12-01 00:00:00',
        'end_time'   => '2030-12-31 00:00:00',
        'sort'       => 1,
    ];

    protected $fillable = [
        'domain',
        'type',
        'name',
        'image',
        'url',
        'start_time',
        'end_time',
        'sort',
    ];

    const TYPE = [
        1  => '首页上方广告',
        2  => '首页下方广告',
        11 => '详情页广告',
    ];
}
