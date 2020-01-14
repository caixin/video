<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Video\VideoActorsRepository;
use App\Repositories\Video\VideoTagsRepository;

class GetVideoTagsApi extends Command
{
    //命令名稱
    protected $signature = 'api:video_tags';
    //說明文字
    protected $description = '[API] 取得影片標籤及女優';

    protected $videoActorsRepository;
    protected $videoTagsRepository;

    public function __construct(
        VideoActorsRepository $videoActorsRepository,
        VideoTagsRepository $videoTagsRepository
    ) {
        parent::__construct();

        $this->videoActorsRepository = $videoActorsRepository;
        $this->videoTagsRepository = $videoTagsRepository;
    }

    // Console 執行的程式
    public function handle()
    {
        //女優更新
        $actors = $this->videoActorsRepository->getAllName();
        $result = getVideoApi('actors');
        $insert = [];
        foreach ($result['data']['actors'] as $row) {
            if (!in_array($row['name'], $actors)) {
                $insert[] = [
                    'name'       => $row['name'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 'schedule',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => 'schedule',
                ];
            }
        }
        if ($insert !== []) {
            $this->videoActorsRepository->insert_batch($insert);
        }

        //標籤更新
        $tags = $this->videoTagsRepository->getAllName();
        $result = getVideoApi('tags');
        $insert = [];
        foreach ($result['data']['tags'] as $row) {
            if (!in_array($row['name'], $tags)) {
                $insert[] = [
                    'name'       => $row['name'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 'schedule',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => 'schedule',
                ];
            }
        }
        if ($insert !== []) {
            $this->videoTagsRepository->insert_batch($insert);
        }
    }
}
