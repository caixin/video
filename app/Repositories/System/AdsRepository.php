<?php

namespace App\Repositories\System;

use App\Repositories\AbstractRepository;
use Models\System\Ads;

class AdsRepository extends AbstractRepository
{
    public function __construct(Ads $entity)
    {
        parent::__construct($entity);
    }

    public function _do_search()
    {
        if (isset($this->_search['domain'])) {
            $this->db = $this->db->where(function ($query) {
                $query->where('domain', '=', '')
                    ->orWhereRaw("FIND_IN_SET('".$this->_search['domain']."', domain)");
            });
        }

        if (isset($this->_search['type'])) {
            $this->db = $this->db->where('type', '=', $this->_search['type']);
        }

        if (isset($this->_search['enabled'])) {
            $date = date('Y-m-d H:i:s');
            $this->db = $this->db->where('start_time', '<=', $date)->where('end_time', '>=', $date);
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
        'id'         => '流水号',
        'type'       => '位置',
        'name'       => '名称',
        'image'      => '图片',
        'url'        => '连结',
        'start_time' => '开始时间',
        'end_time'   => '结束时间',
        'sort'       => '排序',
    ];
}
