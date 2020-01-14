<?php

namespace App\Services\Video;

use App\Repositories\Video\VideoActorsRepository;

class VideoActorsService
{
    protected $videoActorsRepository;

    public function __construct(VideoActorsRepository $videoActorsRepository)
    {
        $this->videoActorsRepository = $videoActorsRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->videoActorsRepository->search($search)
            ->order($order)->paginate(session('per_page'))
            ->result()->appends($input);

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function save($row, $id)
    {
        $this->videoActorsRepository->save($row, $id);
    }
}
