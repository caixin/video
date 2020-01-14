<?php

namespace App\Services\System;

use App\Repositories\System\DomainSettingRepository;

class DomainSettingService
{
    protected $domainSettingRepository;

    public function __construct(DomainSettingRepository $domainSettingRepository)
    {
        $this->domainSettingRepository = $domainSettingRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->domainSettingRepository->search($search)
            ->order($order)->paginate(session('per_page'))
            ->result()->appends($input);

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function create($input)
    {
        $row = $this->domainSettingRepository->getEntity();
        if ($input['id'] > 0) {
            $row = $this->domainSettingRepository->row($input['id']);
        }

        return [
            'row' => $row,
        ];
    }

    public function store($row)
    {
        $row = array_map('strval', $row);
        $this->domainSettingRepository->create($row);
    }

    public function show($id)
    {
        return [
            'row' => $this->domainSettingRepository->find($id),
        ];
    }

    public function update($row, $id)
    {
        $row = array_map('strval', $row);
        $this->domainSettingRepository->update($row, $id);
    }

    public function destroy($id)
    {
        $this->domainSettingRepository->delete($id);
    }
}
