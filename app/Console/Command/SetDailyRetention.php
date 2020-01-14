<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\User\UserRepository;
use App\Repositories\Pmtools\DailyRetentionRepository;

class SetDailyRetention extends Command
{
    //命令名稱
    protected $signature = 'pmtools:daily_retention {--enforce=} {--date=}';
    //說明文字
    protected $description = '[統計]留存率';

    protected $userRepository;
    protected $dailyRetentionRepository;

    public function __construct(
        UserRepository $userRepository,
        DailyRetentionRepository $dailyRetentionRepository
    ) {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->dailyRetentionRepository = $dailyRetentionRepository;
    }

    /**
     * @param string $date 指定執行日期
     * @param int $enforce 強制重新執行
     */
    public function handle()
    {
        $date    = $this->option('date') ?: date('Y-m-d', time()-86400);
        $enforce = $this->option('enforce') ?: 0;

        //強制執行 先刪除已存在的資料
        if ($enforce) {
            $this->dailyRetentionRepository->search(['date'=>$date])->delete();
        }

        //判斷是否已執行過(有資料)
        if ($this->dailyRetentionRepository->search(['date'=>$date])->count() == 0) {
            $now = date('Y-m-d H:i:s');
            //總數
            $all = $this->userRepository->search(['status'=>1])->count();
            $insert = [];
            for ($i = 1; $i <= 6; $i++) {
                $data = $this->userRepository->retention($i);
                $insert[] = [
                    'date'       => $date,
                    'type'       => $i,
                    'all_count'  => $all,
                    'day_count'  => $data['day_count'] ?? 0,
                    'avg_money'  => $data['avg_money'] ?? 0,
                    'created_at' => $now,
                ];
            }
            $this->dailyRetentionRepository->insert_batch($insert);
        }
    }
}
