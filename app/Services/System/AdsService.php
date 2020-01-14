<?php

namespace App\Services\System;

use App\Repositories\System\AdsRepository;

class AdsService
{
    protected $adsRepository;

    public function __construct(AdsRepository $adsRepository)
    {
        $this->adsRepository = $adsRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['sort', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->adsRepository->search($search)
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
        $row = $this->adsRepository->getEntity();
        if ($input['id'] > 0) {
            $row = $this->adsRepository->row($input['id']);
        }
        $row['domain'] = $row['domain'] == '' ? []:explode(',', $row['domain']);

        return [
            'row' => $row,
        ];
    }

    public function store($row)
    {
        $row['domain'] = isset($row['domain']) ? implode(',', $row['domain']):'';
        $row = array_map('strval', $row);
        $this->adsRepository->create($row);
    }

    public function show($id)
    {
        $row = $this->adsRepository->find($id);
        $row['domain'] = $row['domain'] == '' ? []:explode(',', $row['domain']);

        return [
            'row'  => $row,
        ];
    }

    public function update($row, $id)
    {
        $row['domain'] = isset($row['domain']) ? implode(',', $row['domain']):'';
        $row = array_map('strval', $row);
        $this->adsRepository->update($row, $id);
    }

    public function destroy($id)
    {
        $this->adsRepository->delete($id);
    }
}
