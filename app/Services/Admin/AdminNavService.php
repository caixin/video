<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AdminNavRepository;

class AdminNavService
{
    protected $adminNavRepository;

    public function __construct(AdminNavRepository $adminNavRepository)
    {
        $this->adminNavRepository = $adminNavRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['sort', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->adminNavRepository->search($search)
            ->order($order)->result()->toArray();
        $result = $this->treeSort($result);

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function create($input)
    {
        $row = $this->adminNavRepository->getEntity();
        $row['pid'] = $input['pid'] ?? $row['pid'];

        return [
            'row' => $row,
            'nav' => $this->getDropDownList(),
        ];
    }

    public function store($row)
    {
        $row = array_map('strval', $row);
        $this->adminNavRepository->create($row);
    }

    public function show($id)
    {
        return [
            'row' => $this->adminNavRepository->row($id)->toArray(),
            'nav' => $this->getDropDownList(),
        ];
    }

    public function update($row, $id)
    {
        $row = array_map('strval', $row);
        $this->adminNavRepository->update($row, $id);
    }

    public function save($row, $id)
    {
        $this->adminNavRepository->save($row, $id);
    }

    public function destroy($id)
    {
        $this->adminNavRepository->delete($id);
    }

    public function treeSort($result, $pid = 0, $array = [], $prefix = '')
    {
        foreach ($result as $row) {
            if ($row['pid'] == $pid) {
                $row['prefix'] = $prefix;
                $array[] = $row;
                $array = $this->treeSort($result, $row['id'], $array, $prefix . '∟ ');
            }
        }
        return $array;
    }

    public function getDropDownList()
    {
        $result = $this->adminNavRepository->order(['sort','asc'])->result()->toArray();
        $result = $this->treeSort($result);
        $data = [];
        foreach ($result as $row) {
            $data[$row['id']] = $row['prefix'] . $row['name'];
        }
        return $data;
    }
}
