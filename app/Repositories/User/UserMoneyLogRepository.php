<?php

namespace App\Repositories\User;

use App\Repositories\AbstractRepository;
use Models\User\UserMoneyLog;

class UserMoneyLogRepository extends AbstractRepository
{
    public function __construct(UserMoneyLog $entity)
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

        if (isset($this->_search['video'])) {
            $this->db = $this->db->whereHas('video', function ($query) {
                $query->where('name', 'like', '%'.$this->_search['video'].'%');
            });
        }

        if (isset($this->_search['type'])) {
            $this->db = $this->db->where('type', '=', $this->_search['type']);
        }

        if (isset($this->_search['created_at1'])) {
            $this->db = $this->db->where('created_at', '>=', $this->_search['created_at1'] . ' 00:00:00');
        }
        if (isset($this->_search['created_at2'])) {
            $this->db = $this->db->where('created_at', '<=', $this->_search['created_at2'] . ' 23:59:59');
        }

        return $this;
    }

    /**
     * 購買的影片清單(未過期)
     *
     * @param integer $uid 用戶ID
     * @return array
     */
    public function getBuyVideo($uid)
    {
        $where[] = ['uid', '=', $uid];
        $where[] = ['type', '=', 2];
        $where[] = ['video_keyword', '<>', ''];
        $where[] = ['created_at', '>=', date('Y-m-d H:i:s', time()-86400)];
        $result = $this->where($where)->result()->toArray();

        return array_column($result, 'video_keyword');
    }

    /**
     * For 操作日誌用
     *
     * @var array
     */
    public static $columnList = [
        'uid'          => '用戶ID',
        'type'         => '帳變類型',
        'money_before' => '变动前点数',
        'money_add'    => '变动点数',
        'money_after'  => '变动后点数',
        'description'  => '描述',
    ];
}
