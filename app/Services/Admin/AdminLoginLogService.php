<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AdminLoginLogRepository;

class AdminLoginLogService
{
    protected $adminLoginLogRepository;

    public function __construct(AdminLoginLogRepository $adminLoginLogRepository)
    {
        $this->adminLoginLogRepository = $adminLoginLogRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->adminLoginLogRepository->search($search)
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
}
