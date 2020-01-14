<?php

namespace Models\Admin;

use Models\Model;

class AdminActionLog extends Model
{
    const UPDATED_AT = null;

    protected $table = 'admin_action_log';

    protected $fillable = [
        'adminid',
        'route',
        'message',
        'sql_str',
        'ip',
        'status',
        'created_by',
    ];

    const STATUS = [
        1 => '成功',
        0 => '失败',
    ];

    public function admin()
    {
        return $this->belongsTo('Models\Admin\Admin', 'adminid');
    }
}
