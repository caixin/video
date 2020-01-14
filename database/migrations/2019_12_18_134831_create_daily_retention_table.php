<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRetentionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'daily_retention';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->default('1970-01-01')->comment('日期');
            $table->tinyInteger('type')->comment('類型 1.1日內有登入,2.3日內有登入,3.7日內有登入,4.15日內有登入,5.30日內有登入,6.31日以上未登入');
            $table->integer('all_count')->default(0)->comment('總數');
            $table->integer('day_count')->default(0)->comment('人數');
            $table->integer('avg_money')->default(0)->comment('平均點數');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->unique(['date','type']);
        });

        DB::statement("ALTER TABLE `$tableName` comment '每日統計-留存率'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_retention');
    }
}
