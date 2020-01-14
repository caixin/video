<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserLoginLogRepository;
use App\Repositories\Pmtools\ConcurrentUserRepository;
use App\Repositories\Pmtools\DailyAnalysisRepository;

class SetDailyAnalysis extends Command
{
    //命令名稱
    protected $signature = 'pmtools:daily_analysis {--enforce=} {--date=}';
    //說明文字
    protected $description = '[統計]每日活躍用戶數';

    protected $userLoginLogRepository;
    protected $concurrentUserRepository;
    protected $dailyAnalysisRepository;

    public function __construct(
        UserRepository $userRepository,
        UserLoginLogRepository $userLoginLogRepository,
        ConcurrentUserRepository $concurrentUserRepository,
        DailyAnalysisRepository $dailyAnalysisRepository
    ) {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->userLoginLogRepository = $userLoginLogRepository;
        $this->concurrentUserRepository = $concurrentUserRepository;
        $this->dailyAnalysisRepository = $dailyAnalysisRepository;
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
            $this->dailyAnalysisRepository->search(['date'=>$date])->delete();
        }

        //判斷是否已執行過(有資料)
        if ($this->dailyAnalysisRepository->search(['date'=>$date])->count() == 0) {
            $insert = [];
            $now = date('Y-m-d H:i:s');
            //NUU
            $NUU = $this->userRepository->search([
                'status'       => 1,
                'created_at1' => $date,
                'created_at12' => $date,
            ])->count();
            $insert[] = [
                'date'       => $date,
                'type'       => 1,
                'count'      => $NUU,
                'created_at' => $now,
            ];
            //DAU
            $DAU = $this->userLoginLogRepository->getLoginUsers($date, $date);
            $insert[] = [
                'date'       => $date,
                'type'       => 2,
                'count'      => $DAU,
                'created_at' => $now,
            ];
            //WAU
            $WAU = $this->userLoginLogRepository->getLoginUsers(date('Y-m-d', strtotime($date) - 86400 * 6), $date);
            $insert[] = [
                'date'       => $date,
                'type'       => 3,
                'count'      => $WAU,
                'created_at' => $now,
            ];
            //MAU
            $MAU = $this->userLoginLogRepository->getLoginUsers(date('Y-m-01', strtotime($date)), $date);
            $insert[] = [
                'date'       => $date,
                'type'       => 4,
                'count'      => $MAU,
                'created_at' => $now,
            ];
            //DAU-NUU
            $insert[] = [
                'date'       => $date,
                'type'       => 5,
                'count'      => $DAU - $NUU,
                'created_at' => $now,
            ];
            //PCU
            $CCU = $this->concurrentUserRepository->select(['IFNULL(MAX(count),0) count'])->search([
                'per'          => 1,
                'minute_time1' => "$date 00:00:00",
                'minute_time2' => "$date 23:59:59",
            ])->result_one();
            $insert[] = [
                'date'       => $date,
                'type'       => 6,
                'count'      => $CCU['count'],
                'created_at' => $now,
            ];
            //寫入資料
            $this->dailyAnalysisRepository->insert_batch($insert);
        }
    }
}
