<?php

namespace App\Repositories\User;

use DB;
use App\Repositories\AbstractRepository;
use App\Repositories\System\Ip2locationRepository;
use App\Repositories\User\UserMoneyLogRepository;
use Models\User\User;

class UserRepository extends AbstractRepository
{
    protected $ip2locationRepository;
    protected $userMoneyLogRepository;

    public function __construct(
        User $entity,
        Ip2locationRepository $ip2locationRepository,
        UserMoneyLogRepository $userMoneyLogRepository
    ) {
        parent::__construct($entity);
        $this->ip2locationRepository = $ip2locationRepository;
        $this->userMoneyLogRepository = $userMoneyLogRepository;
        $this->is_action_log = false;
    }

    public function create($row)
    {
        if (isset($row['password'])) {
            if ($row['password'] != '') {
                $row['password'] = bcrypt($row['password']);
            } else {
                unset($row['password']);
            }
        }
        if (isset($row['referrer_code'])) {
            if ($referrer = $this->referrerCode($row['referrer_code'], 'decode')) {
                $row['referrer'] = $referrer;
            }
            unset($row['referrer_code']);
        }

        $ip = request()->getClientIp();
        $ip_info = $this->ip2locationRepository->getIpData($ip);
        $ip_info = $ip_info ?? [];
        $row['create_ip']      = $ip;
        $row['create_ip_info'] = json_encode($ip_info);
        $row['create_ua']      = request()->server('HTTP_USER_AGENT');
        $row['token']          = $row['username'];

        return parent::create($row);
    }

    public function update($row, $id=0)
    {
        if (isset($row['password'])) {
            if ($row['password'] != '') {
                $row['password'] = bcrypt($row['password']);
            } else {
                unset($row['password']);
            }
        }
        if (isset($row['referrer_code'])) {
            if ($referrer = $this->referrerCode($row['referrer_code'], 'decode')) {
                $row['referrer'] = $referrer;
            }
            unset($row['referrer_code']);
        }
        if (isset($row['update_info'])) {
            $ip = request()->getClientIp();
            $ip_info = $this->ip2locationRepository->getIpData($ip);
            $ip_info = $ip_info ?? [];
            $row['create_ip']      = $ip;
            $row['create_ip_info'] = json_encode($ip_info);
            $row['create_ua']      = request()->server('HTTP_USER_AGENT');
        }

        return parent::update($row, $id);
    }

    public function _do_search()
    {
        if (isset($this->_search['ids'])) {
            $this->db = $this->db->whereIn('id', $this->_search['ids']);
        }

        if (isset($this->_search['username'])) {
            $this->db = $this->db->where('username', 'like', '%'.$this->_search['username'].'%');
        }

        if (isset($this->_search['referrer_code'])) {
            $uid = $this->referrerCode($this->_search['referrer_code'], 'decode');
            $this->db = $this->db->where('id', '=', $uid);
        }

        if (isset($this->_search['referrer'])) {
            $this->db = $this->db->where('referrer', '=', $this->_search['referrer']);
        }

        if (isset($this->_search['remark'])) {
            $this->db = $this->db->where('remark', 'like', '%'.$this->_search['remark'].'%');
        }

        if (isset($this->_search['status'])) {
            $this->db = $this->db->where('status', '=', $this->_search['status']);
        }

        if (isset($this->_search['login_time1'])) {
            $this->db = $this->db->where('login_time', '>=', $this->_search['login_time1']);
        }
        if (isset($this->_search['login_time2'])) {
            $this->db = $this->db->where('login_time', '<=', $this->_search['login_time2']);
        }

        if (isset($this->_search['active_time1'])) {
            $this->db = $this->db->where('active_time', '>=', $this->_search['active_time1']);
        }
        if (isset($this->_search['active_time2'])) {
            $this->db = $this->db->where('active_time', '<=', $this->_search['active_time2']);
        }

        if (isset($this->_search['created_at1'])) {
            $this->db = $this->db->where('created_at', '>=', $this->_search['created_at1'] . ' 00:00:00');
        }
        if (isset($this->_search['created_at2'])) {
            $this->db = $this->db->where('created_at', '<=', $this->_search['created_at2'] . ' 23:59:59');
        }
        if (isset($this->_search['no_login_day'])) {
            $this->db = $this->db->whereRaw('login_time >= created_at + INTERVAL ? DAY', [$this->_search['no_login_day']]);
        }

        if (isset($this->_search['date_has_login'])) {
            $this->db = $this->db->whereHas('loginLog', function ($query) {
                $query->where('created_at', '>=', $this->_search['date_has_login'] . ' 00:00:00')
                    ->where('created_at', '<=', $this->_search['date_has_login'] . ' 23:59:59');
            });
        }

        return $this;
    }

    /**
     * 依帳號取得會員資料
     *
     * @param string $username
     * @return array
     */
    public function getDataByUsername($username)
    {
        $where[] = ['username', '=', $username];
        return $this->where($where)->result_one();
    }

    /**
     * 取得推薦列表
     *
     * @param int $uid
     * @return array
     */
    public function getReferrerList($uid)
    {
        $where[] = ['referrer', '=', $uid];
        return $this->where($where)->paginate(10)->result();
    }

    /**
     * 變動餘額+LOG
     *
     * @param int $uid 用戶ID
     * @param int $type 帳變類別
     * @param int $add_money 帳變金額
     * @param string $description 描述
     * @param string $video_keyword 訂單號
     * @return void
     */
    public function addMoney($uid, $type, $add_money, $description, $video_keyword='')
    {
        $user = $this->row($uid);
        //找不到帳號則跳出
        if ($user === null) {
            return;
        }
        //帳變金額為0則不動作
        if ((int)$add_money == 0) {
            return;
        }

        $data = [
            'uid'           => $uid,
            'type'          => $type,
            'video_keyword' => $video_keyword,
            'money_before'  => $user['money'],
            'money_add'     => $add_money,
            'money_after'   => $user['money'] + $add_money,
            'description'   => $description,
        ];

        DB::transaction(function () use ($data) {
            $this->update([
                'money' => $data['money_after'],
            ], $data['uid']);
            $this->userMoneyLogRepository->create($data);
        });
    }

    /**
     * UID-邀請碼轉換
     *
     * @param string $val 轉換值
     * @param string $method 回傳code OR uid
     * @return string
     */
    public function referrerCode($val, $method='encode')
    {
        if ($method == 'encode') {
            $user = $this->row($val);
            $checkcode = substr(base_convert($user['verify_code'], 10, 32), -2);
            $hex = dechex(($val+100000) ^ 19487);
            return $checkcode.$hex;
        }
        if ($method == 'decode') {
            $checkcode = substr($val, 0, 2);
            $uid = (hexdec(substr($val, 2)) ^ 19487) - 100000;
            $user = $this->row($uid);
            $checkcode = substr(base_convert($user['verify_code'], 10, 32), -2);
            if ($checkcode == substr($val, 0, 2)) {
                return $uid;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * 計算留存率
     *
     * @param int $type 類型
     * @return array
     */
    public function retention($type)
    {
        $where[] = ['status', '=', 1];
        switch ($type) {
            case 1: $where[] = ['login_time', '>=', date('Y-m-d', time() - 86400)]; break;
            case 2: $where[] = ['login_time', '>=', date('Y-m-d', time() - 86400 * 3)]; break;
            case 3: $where[] = ['login_time', '>=', date('Y-m-d', time() - 86400 * 7)]; break;
            case 4: $where[] = ['login_time', '>=', date('Y-m-d', time() - 86400 * 15)]; break;
            case 5: $where[] = ['login_time', '>=', date('Y-m-d', time() - 86400 * 30)]; break;
            case 6: $where[] = ['login_time', '<', date('Y-m-d', time() - 86400 * 30)]; break;
        }
        return $this->select(['COUNT(id) day_count', 'ROUND(AVG(money)) avg_money'])
                ->where($where)->result_one()->toArray();
    }

    /**
     * 留存率區間
     *
     * @param string $starttime 起始時間
     * @param string $endtime 結束時間
     * @param int $type 類型
     * @return array
     */
    public function retentionAnalysis($starttime, $endtime, $type)
    {
        $search['status'] = 1;
        $search['created_at1'] = $starttime;
        $search['created_at2'] = $endtime;
        switch ($type) {
            case 1: $search['no_login_day'] = 1; break;
            case 2: $search['no_login_day'] = 3; break;
            case 3: $search['no_login_day'] = 7; break;
            case 4: $search['no_login_day'] = 15; break;
            case 5: $search['no_login_day'] = 30; break;
            case 6: $search['no_login_day'] = 60; break;
            case 7: $search['no_login_day'] = 90; break;
            case 8: $search['login_time1'] = date('Y-m-d', time() - 86400 * 7); break;
        }
        return $this->select(['IFNULL(COUNT(id),0) count', 'IFNULL(ROUND(AVG(money)),0) avg_money'])
                ->search($search)->result_one()->toArray();
    }

    /**
     * 新帳號留存率
     *
     * @param int $type 類型
     * @param string $date 日期
     * @return array
     */
    public function retentionDaily($type, $date)
    {
        $day = 1;
        switch ($type) {
            case 1: $day = 1; break;
            case 2: $day = 3; break;
            case 3: $day = 7; break;
            case 4: $day = 15; break;
            case 5: $day = 30; break;
        }
        $createdate = date('Y-m-d', strtotime($date) - 86400 * $day);
        $search['status'] = 1;
        $search['created_at1'] = $createdate;
        $search['created_at2'] = $createdate;
        $array['all_count'] = $this->search($search)->count();

        $search['date_has_login'] = $date;
        $array['day_count'] = $this->search($search)->count();
        return $array;
    }

    /**
     * For 操作日誌用
     *
     * @var array
     */
    public static $columnList = [
        'id'        => '用戶ID',
        'username'  => '用户名称',
        'password'  => '用户密码',
        'money'     => '货币',
        'remark'    => '备注',
        'status'    => '状态',
        'free_time' => '免费看到期日',
    ];
}
