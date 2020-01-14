<?php

namespace App\Services\User;

use App\Repositories\User\UserLoginLogRepository;
use App\Repositories\User\UserRepository;

class UserLoginLogService
{
    protected $userLoginLogRepository;
    protected $userRepository;

    public function __construct(
        UserLoginLogRepository $userLoginLogRepository,
        UserRepository $userRepository
    ) {
        $this->userLoginLogRepository = $userLoginLogRepository;
        $this->userRepository = $userRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->userLoginLogRepository->search($search)
            ->order($order)->paginate(session('per_page'))
            ->result()->appends($input);

        foreach ($result as $key => $row) {
            $info = json_decode($row['ip_info'], true);
            $row['country'] = empty($info) ? '' : "$info[country_name]/$info[region_name]";
            $result[$key] = $row;
        }

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function setLog($uid)
    {
        //當天第一次登入有簽到禮(排除免費看會員)
        $user = $this->userRepository->row($uid);
        if ($user['free_time'] < date('Y-m-d H:i:s')) {
            $date = date('Y-m-d');
            $result = $this->userLoginLogRepository->search([
                'uid'         => $uid,
                'created_at1' => $date,
                'created_at2' => $date,
            ])->result();
            $sysconfig = view()->shared('sysconfig');
            if ($result->toArray() === []) {
                $this->userRepository->addMoney($uid, 3, $sysconfig['daily_signin'], '每日签到');
            }
        }
        //寫入LOG
        $this->userLoginLogRepository->create([
            'uid' => $uid,
        ]);
    }

    public function loginMap($input)
    {
        $search_params = param_process($input, ['id', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['created_at1'] = $search['created_at1'] ?? date('Y-m-d H:i:s', time()-86400);
        $search['created_at2'] = $search['created_at2'] ?? date('Y-m-d H:i:s');

        $where[] = ['created_at', '>=', $search['created_at1']];
        $where[] = ['created_at', '<=', $search['created_at2']];
        $result = $this->userLoginLogRepository
            ->select(['uid','ip_info'])
            ->where($where)
            ->group(['uid','ip_info'])
            ->result();

        $table = [];
        foreach ($result as $row) {
            $info = json_decode($row['ip_info'], true);
            if ($info == []) {
                continue;
            }
            $table[] = [
                'lat' => (float)$info['latitude'],
                'lng' => (float)$info['longitude']
            ];
        }

        return [
            'table'      => $table,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }
}
