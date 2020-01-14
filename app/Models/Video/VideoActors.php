<?php

namespace Models\Video;

use Models\Model;

class VideoActors extends Model
{
    protected $table = 'video_actors';

    protected $fillable = [
        'name',
        'hot',
        'created_by',
        'updated_by',
    ];

    const HOT = [
        1 => '是',
        0 => '否',
    ];
}
