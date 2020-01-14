<?php

namespace App\Repositories\Admin;

use App\Repositories\AbstractRepository;
use Models\Admin\AdminLoginLog;

class AdminLoginLogRepository extends AbstractRepository
{
    public function __construct(AdminLoginLog $entity)
    {
        parent::__construct($entity);
        $this->is_action_log = false;
    }

    public function _do_search()
    {
        if (isset($this->_search['username'])) {
            $this->db = $this->db->whereHas('admin', function ($query) {
                $query->where('username', 'like', '%'.$this->_search['username'].'%');
            });
        }

        if (isset($this->_search['ip'])) {
            $this->db = $this->db->where('ip', '=', $this->_search['ip']);
        }

        if (isset($this->_search['created_at1'])) {
            $this->db = $this->db->where('created_at', '>=', $this->_search['created_at1'] . ' 00:00:00');
        }
        if (isset($this->_search['created_at2'])) {
            $this->db = $this->db->where('created_at', '<=', $this->_search['created_at2'] . ' 23:59:59');
        }

        return $this;
    }
}
