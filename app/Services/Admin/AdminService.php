<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AdminRepository;
use App\Repositories\Admin\AdminRoleRepository;

class AdminService
{
    protected $adminRepository;
    protected $adminRoleRepository;

    public function __construct(
        AdminRepository $adminRepository,
        AdminRoleRepository $adminRoleRepository
    ) {
        $this->adminRepository = $adminRepository;
        $this->adminRoleRepository = $adminRoleRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->adminRepository->search($search)
            ->order($order)->paginate(session('per_page'))
            ->result()->appends($input);

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
            'role'       => [1=>'超级管理者']+$this->adminRoleRepository->getRoleList(),
        ];
    }

    public function create($input)
    {
        $row = $this->adminRepository->getEntity();

        return [
            'row'  => $row,
            'role' => $this->adminRoleRepository->getRoleList(),
        ];
    }

    public function store($row)
    {
        $row = array_map('strval', $row);
        $this->adminRepository->create($row);
    }

    public function show($id)
    {
        return [
            'row' => $this->adminRepository->find($id),
            'role' => $this->adminRoleRepository->getRoleList(),
        ];
    }

    public function update($row, $id)
    {
        $row = array_map('strval', $row);
        $this->adminRepository->update($row, $id);
    }

    public function save($row, $id)
    {
        $this->adminRepository->save($row, $id);
    }

    public function destroy($id)
    {
        $this->adminRepository->delete($id);
    }
}
