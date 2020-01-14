<?php

namespace App\Repositories\System;

use App\Repositories\AbstractRepository;
use Models\System\Message;

class MessageRepository extends AbstractRepository
{
    public function __construct(Message $entity)
    {
        parent::__construct($entity);
        $this->is_action_log = false;
    }

    public function _do_search()
    {
        if (isset($this->_search['uid'])) {
            $this->db = $this->db->where('uid', '=', $this->_search['uid']);
        }
        if (isset($this->_search['username'])) {
            $this->db = $this->db->whereHas('user', function ($query) {
                $query->where('username', 'like', '%'.$this->_search['username'].'%');
            });
        }

        if (isset($this->_search['type'])) {
            $this->db = $this->db->where('type', '=', $this->_search['type']);
        }

        if (isset($this->_search['created_at1'])) {
            $this->db = $this->db->where('created_at', '>=', $this->_search['created_at1'].' 00:00:00');
        }

        if (isset($this->_search['created_at2'])) {
            $this->db = $this->db->where('created_at', '<=', $this->_search['created_at2'].' 23:59:59');
        }
        return $this;
    }

    /**
     * For 操作日誌用
     *
     * @var array
     */
    public static $columnList = [
        'id'      => '流水号',
        'uid'     => '用户ID',
        'type'    => '问题类型',
        'content' => '内容',
    ];
}
