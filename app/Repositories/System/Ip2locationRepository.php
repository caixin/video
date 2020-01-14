<?php

namespace App\Repositories\System;

use App\Repositories\AbstractRepository;
use Models\System\Ip2location;

class Ip2locationRepository extends AbstractRepository
{
    public function __construct(Ip2location $entity)
    {
        parent::__construct($entity);
    }

    public function _do_search()
    {
        if (isset($this->_search['ip_from'])) {
            $this->db = $this->db->where('ip_from', '<=', $this->_search['ip_from']);
        }
        if (isset($this->_search['ip_to'])) {
            $this->db = $this->db->where('ip_to', '>=', $this->_search['ip_to']);
        }
        return $this;
    }

    /**
     * 取得IP位置資訊
     * @param string $ip IP位址
     * @return array IP資訊
     */
    public function getIpData($ip)
    {
        return $this->search(['ip_from'=>ip2long($ip)])
            ->order(['ip_from','desc'])
            ->result_one();
    }
}
