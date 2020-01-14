<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyAnalysisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'daily_analysis';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->default('1970-01-01')->comment('日期');
            $table->tinyInteger('type')->comment('類型 1.每日新增遊戲帳號數(NUU) 2.每日不重覆登入帳號數(DAU) 3.每週不重複登入帳號數(WAU) 4.每月不重覆登入帳號數(MAU) 5.累積不重覆登入帳號數(UU) 6.最大同時在線帳號數(PCU)');
            $table->integer('count')->default(0)->comment('人數');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->unique(['date','type']);
        });

        DB::statement("ALTER TABLE `$tableName` comment '每日統計-活躍人數'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_analysis');
    }
}
