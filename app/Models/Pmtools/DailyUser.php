<?php

namespace Models\Pmtools;

use Models\Model;

class DailyUser extends Model
{
    const UPDATED_AT = null;
    
    protected $table = 'daily_user';

    protected $fillable = [
        'uid',
        'date',
        'login',
        'point',
        'consecutive',
    ];
}
