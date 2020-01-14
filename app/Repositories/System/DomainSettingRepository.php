<?php

namespace App\Repositories\System;

use App\Repositories\AbstractRepository;
use Models\System\DomainSetting;

class DomainSettingRepository extends AbstractRepository
{
    public function __construct(DomainSetting $entity)
    {
        parent::__construct($entity);
    }

    public function _do_search()
    {
        if (isset($this->_search['domain'])) {
            $this->db = $this->db->where('domain', '=', $this->_search['domain']);
            unset($this->_search['domain']);
        }

        if (isset($this->_search['created_at1'])) {
            $this->db = $this->db->where('created_at', '>=', $this->_search['created_at1']);
        }

        if (isset($this->_search['created_at2'])) {
            $this->db = $this->db->where('created_at', '<=', $this->_search['created_at2']);
        }
        return $this;
    }

    /**
     * For 操作日誌用
     *
     * @var array
     */
    public static $columnList = [
        'id'          => '流水号',
        'domain'      => '网域',
        'title'       => '标题',
        'keyword'     => '关键字',
        'description' => '描述',
        'baidu'       => '百度统计代码',
    ];
}
