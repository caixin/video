<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AdminActionLogRepository;

class AdminActionLogService
{
    protected $adminActionLogRepository;

    public function __construct(AdminActionLogRepository $adminActionLogRepository)
    {
        $this->adminActionLogRepository = $adminActionLogRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->adminActionLogRepository->search($search)
            ->order($order)->paginate(session('per_page'))
            ->result()->appends($input);

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }
}
