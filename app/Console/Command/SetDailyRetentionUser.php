<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\User\UserRepository;
use App\Repositories\Pmtools\DailyRetentionUserRepository;

class SetDailyRetentionUser extends Command
{
    //命令名稱
    protected $signature = 'pmtools:daily_retention_user {--enforce=} {--date=}';
    //說明文字
    protected $description = '[統計]新帐号留存率';

    protected $userRepository;
    protected $dailyRetentionUserRepository;

    public function __construct(
        UserRepository $userRepository,
        DailyRetentionUserRepository $dailyRetentionUserRepository
    ) {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->dailyRetentionUserRepository = $dailyRetentionUserRepository;
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
            $this->dailyRetentionUserRepository->search(['date'=>$date])->delete();
        }

        //判斷是否已執行過(有資料)
        if ($this->dailyRetentionUserRepository->search(['date'=>$date])->count() == 0) {
            $now = date('Y-m-d H:i:s');
            for ($i = 1; $i <= 5; $i++) {
                $arr = $this->userRepository->retentionDaily($i, $date);
                $insert[] = [
                    'date'       => $date,
                    'type'       => $i,
                    'all_count'  => $arr['all_count'],
                    'day_count'  => $arr['day_count'],
                    'percent'    => $arr['all_count'] == 0 ? 0 : round($arr['day_count'] / $arr['all_count'] * 100),
                    'created_at' => $now,
                ];
            }
            $this->dailyRetentionUserRepository->insert_batch($insert);
        }
    }
}
