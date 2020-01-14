<?php

namespace App\Services\Video;

use App\Repositories\Video\VideoTagsRepository;

class VideoTagsService
{
    protected $videoTagsRepository;

    public function __construct(VideoTagsRepository $videoTagsRepository)
    {
        $this->videoTagsRepository = $videoTagsRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $result = $this->videoTagsRepository->search($search)
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
        $this->videoTagsRepository->save($row, $id);
    }
}
