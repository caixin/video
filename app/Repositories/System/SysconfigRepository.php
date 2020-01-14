<?php

namespace App\Repositories\System;

use App\Repositories\AbstractRepository;
use Models\System\Sysconfig;

class SysconfigRepository extends AbstractRepository
{
    public function __construct(Sysconfig $entity)
    {
        parent::__construct($entity);
    }

    public function _do_search()
    {
        if (isset($this->_search['skey'])) {
            $this->db = $this->db->where('skey', '=', $this->_search['skey']);
        }
        return $this;
    }

    /**
     * 取得網站基本設置
     *
     * @return array
     */
    public function getSysconfig()
    {
        $result = $this->result();
        $data = [];
        foreach ($result as $row) {
            $data[$row['skey']] = $row['type'] == 2 ? intval($row['svalue']) : $row['svalue'];
        }
        return $data;
    }

    /**
     * 取得參數值
     *
     * @param string $key 參數
     * @return string
     */
    public function getValue($key)
    {
        $row = $this->search(['skey'=>$key])->result_one();
        return $row['svalue'] ?? '';
    }
}
