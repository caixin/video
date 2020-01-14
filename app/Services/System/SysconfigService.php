<?php

namespace App\Services\System;

use App\Repositories\System\SysconfigRepository;

class SysconfigService
{
    protected $sysconfigRepository;

    public function __construct(SysconfigRepository $sysconfigRepository)
    {
        $this->sysconfigRepository = $sysconfigRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['sort', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $where[] = ['groupid', '>', 0];
        $result = $this->sysconfigRepository->where($where)
                    ->search($search)->order($order)
                    ->result()->toArray();
        $result = $this->groupList($result);

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
            'groupid'    => $search['groupid'] ?? 1,
        ];
    }

    public function create($input)
    {
        $row = $this->sysconfigRepository->getEntity();

        return [
            'row' => $row,
        ];
    }

    public function store($row)
    {
        $row = array_map('strval', $row);
        $this->sysconfigRepository->create($row);
    }

    public function update($data)
    {
        foreach ($data['skey'] as $id => $svalue) {
            $this->sysconfigRepository->update([
                'svalue' => $svalue,
                'sort'   => $data['sort'][$id],
            ], $id);
        }
    }

    public function destroy($id)
    {
        $this->sysconfigRepository->delete($id);
    }

    private function groupList($result)
    {
        $data = [];
        foreach ($result as $row) {
            $data[$row['groupid']][] = $row;
        }
        ksort($data);
        return $data;
    }
}
