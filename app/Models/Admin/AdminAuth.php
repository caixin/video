<?php

namespace Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminAuth extends Authenticatable
{
    protected $table = 'admin';

    protected $fillable = [
        'username', 'password',
    ];
}
