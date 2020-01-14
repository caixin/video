<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Video\VideoRepository;

class TestVideoApi extends Command
{
    //命令名稱
    protected $signature = 'test:video';
    //說明文字
    protected $description = '[TEST] 测试视频API端口';

    protected $videoRepository;

    public function __construct(VideoRepository $videoRepository)
    {
        parent::__construct();

        $this->videoRepository = $videoRepository;
    }

    //Console 執行的程式
    public function handle()
    {
        $domain = config('video.domain');
        $au     = config('video.au');
        $apiKey = config('video.key');
        $e      = time() + 2000;
        $token  = md5("$apiKey/list?au=$au&e=$e");
        echo "$domain/list?au=$au&e=$e&token=$token";
        //影片更新
        $result = getVideoApi('list');
        print_r($result);
    }
}
