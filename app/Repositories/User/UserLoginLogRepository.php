<?php

namespace App\Repositories\User;

use App\Repositories\AbstractRepository;
use App\Repositories\System\Ip2locationRepository;
use Models\User\UserLoginLog;

class UserLoginLogRepository extends AbstractRepository
{
    protected $ip2locationRepository;

    public function __construct(UserLoginLog $entity, Ip2locationRepository $ip2locationRepository)
    {
        parent::__construct($entity);
        $this->ip2locationRepository = $ip2locationRepository;
        $this->is_action_log = false;
    }

    public function create($row)
    {
        $ip = request()->getClientIp();
        $ip_info = $this->ip2locationRepository->getIpData($ip);
        $ip_info = $ip_info ?? [];

        $row['ip']      = $ip;
        $row['ip_info'] = json_encode($ip_info);
        $row['ua']      = request()->server('HTTP_USER_AGENT');

        return parent::create($row);
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
        if (isset($this->_search['status'])) {
            $this->db = $this->db->whereHas('user', function ($query) {
                $query->where('status', '=', $this->_search['status']);
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

    /**
     * 取得區間內不重複登入帳號數
     *
     * @param string $startdate 起始時間
     * @param string $enddate   結束時間
     * @return int 人數
     */
    public function getLoginUsers($startdate, $enddate)
    {
        return $this->select(['uid'])->search([
            'status'      => 1,
            'created_at1' => $startdate,
            'created_at2' => $enddate,
        ])->group('uid')->result()->count();
    }
}
