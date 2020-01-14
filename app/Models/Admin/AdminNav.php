<?php

namespace Models\Admin;

use Models\Model;

class AdminNav extends Model
{
    protected $table = 'admin_nav';

    protected $attributes = [
        'route1' => '',
        'route2' => '',
        'sort'   => 0,
        'status' => 1,
    ];

    protected $fillable = [
        'pid',
        'icon',
        'name',
        'route',
        'route1',
        'route2',
        'path',
        'sort',
        'status',
        'created_by',
        'updated_by',
    ];

    const PREFIX_COLOR = [
        ''     => 'red',
        '∟ '   => 'green',
        '∟ ∟ ' => 'black',
    ];

    const STATUS = [
        1 => '开启',
        0 => '关闭',
    ];

    public function parent()
    {
        return $this->hasOne(get_class($this), $this->getKeyName(), 'pid');
    }

    public function children()
    {
        return $this->hasMany(get_class($this), 'pid', $this->getKeyName());
    }
}
