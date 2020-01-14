<?php

namespace Models\Video;

use Models\Model;

class Video extends Model
{
    protected $table = 'video';

    protected $fillable = [
        'keyword',
        'name',
        'publish',
        'actors',
        'tags',
        'pic_b',
        'pic_s',
        'url',
        'hls',
        'created_by',
        'updated_by',
    ];

    const STATUS = [
        0 => '已过期',
        1 => '正常',
    ];
}
