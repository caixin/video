<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\GetVideoApi::class,
        \App\Console\Commands\GetVideoTagsApi::class,
        \App\Console\Commands\SetConcurrentUser::class,
        \App\Console\Commands\SetDailyUser::class,
        \App\Console\Commands\SetDailyAnalysis::class,
        \App\Console\Commands\SetDailyRetention::class,
        \App\Console\Commands\SetDailyRetentionUser::class,
        \App\Console\Commands\TestVideoApi::class,
        \App\Console\Commands\UpdateVideo::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //每15分鐘執行更新視頻
        $schedule->command('api:video')->everyFifteenMinutes();
        //每小時執行過濾過期視頻
        $schedule->command('update:video')->hourly();
        //每天執行更新視頻標籤
        $schedule->command('api:video_tags')->daily();
        //CCU每分鐘
        $schedule->command('pmtools:ccu --per=1')->everyMinute();
        //CCU每5分鐘
        $schedule->command('pmtools:ccu --per=5')->everyFiveMinutes();
        //CCU每10分鐘
        $schedule->command('pmtools:ccu --per=10')->everyTenMinutes();
        //CCU每30分鐘
        $schedule->command('pmtools:ccu --per=30')->everyThirtyMinutes();
        //CCU每60分鐘
        $schedule->command('pmtools:ccu --per=60')->hourly();
        //每天凌晨0點5分執行統計用戶資訊
        $schedule->command('pmtools:daily_user')->dailyAt('0:05');
        //每天凌晨0點10分執行統計活躍用戶數
        $schedule->command('pmtools:daily_analysis')->dailyAt('0:10');
        //每天凌晨0點15分執行統計留存率
        $schedule->command('pmtools:daily_retention')->dailyAt('0:15');
        //每天凌晨0點20分執行統計新帳號留存率
        $schedule->command('pmtools:daily_retention_user')->dailyAt('0:20');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
