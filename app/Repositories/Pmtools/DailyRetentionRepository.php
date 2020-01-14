<?php

namespace App\Repositories\Pmtools;

use App\Repositories\AbstractRepository;
use Models\Pmtools\DailyRetention;

class DailyRetentionRepository extends AbstractRepository
{
    public function __construct(DailyRetention $entity)
    {
        parent::__construct($entity);
        $this->is_action_log = false;
    }

    public function _do_search()
    {
        if (isset($this->_search['type'])) {
            $this->db = $this->db->where('type', '=', $this->_search['type']);
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
