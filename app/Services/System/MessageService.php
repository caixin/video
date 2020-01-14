<?php

namespace App\Services\System;

use App\Repositories\System\MessageRepository;

class MessageService
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['created_at1'] = $search['created_at1'] ?? date('Y-m-d', time()-86400*30);
        $search['created_at2'] = $search['created_at2'] ?? date('Y-m-d');

        $result = $this->messageRepository->search($search)
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
        $row = $this->messageRepository->getEntity();

        return [
            'row' => $row,
        ];
    }

    public function store($row)
    {
        $row = array_map('strval', $row);
        $this->messageRepository->create($row);
    }

    public function show($id)
    {
        $row = $this->messageRepository->find($id);

        return [
            'row'  => $row,
        ];
    }

    public function update($row, $id)
    {
        $row = array_map('strval', $row);
        $this->messageRepository->update($row, $id);
    }

    public function destroy($id)
    {
        $this->messageRepository->delete($id);
    }
}
