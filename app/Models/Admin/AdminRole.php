<?php

namespace Models\Admin;

use Models\Model;

class AdminRole extends Model
{
    protected $table = 'admin_role';

    protected $fillable = [
        'name',
        'allow_operator',
        'allow_nav',
        'created_by',
        'updated_by',
    ];
}
