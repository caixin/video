<?php

namespace App\Repositories\Pmtools;

use App\Repositories\AbstractRepository;
use Models\Pmtools\ConcurrentUser;

class ConcurrentUserRepository extends AbstractRepository
{
    public function __construct(ConcurrentUser $entity)
    {
        parent::__construct($entity);
        $this->is_action_log = false;
    }

    public function _do_search()
    {
        if (isset($this->_search['per'])) {
            $this->db = $this->db->where('per', '=', $this->_search['per']);
        }
        
        if (isset($this->_search['minute_time'])) {
            $this->db = $this->db->where('minute_time', '=', $this->_search['minute_time']);
        }
        if (isset($this->_search['minute_time1'])) {
            $this->db = $this->db->where('minute_time', '>=', $this->_search['minute_time1']);
        }
        if (isset($this->_search['minute_time2'])) {
            $this->db = $this->db->where('minute_time', '<=', $this->_search['minute_time2']);
        }

        return $this;
    }
}
