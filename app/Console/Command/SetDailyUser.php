<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\User\UserLoginLogRepository;
use App\Repositories\User\UserMoneyLogRepository;
use App\Repositories\Pmtools\DailyUserRepository;

class SetDailyUser extends Command
{
    //命令名稱
    protected $signature = 'pmtools:daily_user {--enforce=} {--date=}';
    //說明文字
    protected $description = '[統計]每日登入會員統計';

    protected $userLoginLogRepository;
    protected $userMoneyLogRepository;
    protected $dailyUserRepository;

    public function __construct(
        UserLoginLogRepository $userLoginLogRepository,
        UserMoneyLogRepository $userMoneyLogRepository,
        DailyUserRepository $dailyUserRepository
    ) {
        parent::__construct();

        $this->userLoginLogRepository = $userLoginLogRepository;
        $this->userMoneyLogRepository = $userMoneyLogRepository;
        $this->dailyUserRepository = $dailyUserRepository;
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
            $this->dailyUserRepository->search(['date'=>$date])->delete();
        }

        //判斷是否已執行過(有資料)
        if ($this->dailyUserRepository->search(['date'=>$date])->count() == 0) {
            //取出前一天的連續登入
            $result = $this->dailyUserRepository->select(['uid','consecutive'])->search([
                'date' => date('Y-m-d', strtotime($date) - 86400),
            ])->result()->toArray();
            $consecutive = array_column($result, 'consecutive', 'uid');
            //當天登入會員
            $result = $this->userLoginLogRepository->select(['uid','count(uid) count'])->search([
                'created_at1' => $date,
                'created_at2' => $date,
            ])->group('uid')->result();
            $insert = [];
            foreach ($result as $row) {
                //消費點數
                $moneylog = $this->userMoneyLogRepository->select(['SUM(money_add*-1) point'])->search([
                    'uid'         => $row['uid'],
                    'type'        => 2,
                    'created_at1' => $date,
                    'created_at2' => $date,
                ])->result_one();
                
                $insert[] = [
                    'date'        => $date,
                    'uid'         => $row['uid'],
                    'login'       => $row['count'],
                    'point'       => $moneylog['point'] ?? 0,
                    'consecutive' => ($consecutive[$row['uid']] ?? 0) + 1,
                    'created_at'  => date('Y-m-d H:i:s'),
                ];
            }
            $this->dailyUserRepository->insert_batch($insert);
        }
    }
}
