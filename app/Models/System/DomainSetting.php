<?php

namespace Models\System;

use Models\Model;

class DomainSetting extends Model
{
    protected $table = 'domain_setting';

    protected $fillable = [
        'domain',
        'title',
        'keyword',
        'description',
        'baidu',
    ];
}
