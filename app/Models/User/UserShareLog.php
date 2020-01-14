<?php

namespace Models\User;

use Models\Model;

class UserShareLog extends Model
{
    const UPDATED_AT = null;
    
    protected $table = 'user_share_log';

    protected $fillable = [
        'uid',
        'ip',
        'ip_info',
        'ua',
    ];

    public function user()
    {
        return $this->belongsTo('Models\User\User', 'uid');
    }
}
