<?php

namespace App\Repositories\Pmtools;

use App\Repositories\AbstractRepository;
use Models\Pmtools\DailyUser;

class DailyUserRepository extends AbstractRepository
{
    public function __construct(DailyUser $entity)
    {
        parent::__construct($entity);
        $this->is_action_log = false;
    }

    public function _do_search()
    {
        if (isset($this->_search['uid'])) {
            $this->db = $this->db->where('uid', '=', $this->_search['uid']);
        }
        
        if (isset($this->_search['date'])) {
            $this->db = $this->db->where('date', '=', $this->_search['date']);
        }
        if (isset($this->_search['date1'])) {
            $this->db = $this->db->where('date', '>=', $this->_search['date1']);
        }
        if (isset($this->_search['date2'])) {
            $this->db = $this->db->where('date', '<=', $this->_search['date2']);
        }

        return $this;
    }
}
