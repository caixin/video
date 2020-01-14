<?php

namespace App\Services\User;

use App\Repositories\User\UserMoneyLogRepository;

class UserMoneyLogService
{
    protected $userMoneyLogRepository;

    public function __construct(UserMoneyLogRepository $userMoneyLogRepository)
    {
        $this->userMoneyLogRepository = $userMoneyLogRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->userMoneyLogRepository->search($search)
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
