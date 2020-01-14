<?php

namespace App\Services\Video;

use App\Repositories\Video\VideoRepository;

class VideoService
{
    protected $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['publish', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['status'] = $search['status'] ?? 1;

        $result = $this->videoRepository->search($search)
            ->order($order)->paginate(session('per_page'))
            ->result()->appends($input);

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function getList($request)
    {
        $per_page = $request->per_page ?: 10;

        $search['status'] = 1;
        if ($request->search) {
            $search['name'] = $request->search;
        }
        if ($request->tags) {
            $search['tags'] = $request->tags;
        }

        $result = $this->videoRepository->search($search)
            ->order(['publish'=>'desc','keyword'=>'desc'])
            ->paginate($per_page)
            ->result();
        $list = [];
        foreach ($result as $row) {
            $list[] = [
                'keyword'   => $row['keyword'],
                'name'      => $row['name'],
                'publish'   => $row['publish'],
                'actors'    => $row['actors'],
                'tags'      => $row['tags'],
                'pic_big'   => $row['pic_b'],
                'pic_small' => $row['pic_s'],
            ];
        }
        return [
            'list'  => $list,
            'total' => $result->total(),
        ];
    }
}
