<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\User\UserRepository;
use App\Repositories\Pmtools\ConcurrentUserRepository;

class SetConcurrentUser extends Command
{
    //命令名稱
    protected $signature = 'pmtools:ccu {--per=}';
    //說明文字
    protected $description = '[統計]每日登入會員統計';

    protected $userRepository;
    protected $concurrentUserRepository;

    public function __construct(
        UserRepository $userRepository,
        ConcurrentUserRepository $concurrentUserRepository
    ) {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->concurrentUserRepository = $concurrentUserRepository;
    }

    /**
     * @param int $per 每幾分鐘
     */
    public function handle()
    {
        $per = $this->option('per') ?: 1;
        $minute_time = date('Y-m-d H:i:00');

        if ($this->concurrentUserRepository->search(['per'=>$per,'minute_time'=>$minute_time])->count() == 0) {
            $time = date('Y-m-d H:i:s', time() - 60 * (10 + $per));
            $count = $this->userRepository->search(['active_time1'=>$time])->count();
    
            $this->concurrentUserRepository->create([
                'per'         => $per,
                'minute_time' => $minute_time,
                'count'       => $count,
            ]);
        }
    }
}
