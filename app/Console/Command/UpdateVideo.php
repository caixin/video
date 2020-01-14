<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Video\VideoRepository;

class UpdateVideo extends Command
{
    //命令名稱
    protected $signature = 'update:video';
    //說明文字
    protected $description = '[更新] 过滤过期影片';

    protected $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        parent::__construct();

        $this->videoRepository = $videoRepository;
    }

    //Console 執行的程式
    public function handle()
    {
        $this->videoRepository->search([
            'updated_at2' => date('Y-m-d H:i:s', time()-14400)
        ])->update([
            'status' => 0
        ]);
    }
}
