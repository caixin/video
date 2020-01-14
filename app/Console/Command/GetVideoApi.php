<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Video\VideoRepository;

class GetVideoApi extends Command
{
    //命令名稱
    protected $signature = 'api:video';
    //說明文字
    protected $description = '[API] 取得最新影片';

    protected $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        parent::__construct();

        $this->videoRepository = $videoRepository;
    }

    //Console 執行的程式
    public function handle()
    {
        //影片更新
        $result = getVideoApi('list');
        foreach ($result['data']['videos'] as $row) {
            preg_match_all('/.*videos\/(.*)\/b.jpg/m', $row['pic_b'], $matches, PREG_SET_ORDER, 0);
            $keyword = $matches[0][1];

            $this->videoRepository->updateOrInsert(['keyword'=>$keyword], [
                'name'       => $row['name'],
                'publish'    => $row['publish'],
                'actors'     => implode(',', $row['actors']),
                'tags'       => implode(',', $row['tags']),
                'pic_b'      => $row['pic_b'],
                'pic_s'      => $row['pic_s'],
                'url'        => $row['hls'],
                'status'     => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 'schedule',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 'schedule',
            ]);
        }
    }
}
