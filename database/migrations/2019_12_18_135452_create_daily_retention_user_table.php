<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRetentionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'daily_retention_user';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->default('1970-01-01')->comment('日期');
            $table->tinyInteger('type')->comment('類型 1.1天前新帳號,2.3天前新帳號,3.7天前新帳號,4.15天前新帳號,5.30天前新帳號');
            $table->integer('all_count')->default(0)->comment('總數');
            $table->integer('day_count')->default(0)->comment('人數');
            $table->integer('percent')->default(0)->comment('百分比');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->unique(['date','type']);
        });

        DB::statement("ALTER TABLE `$tableName` comment '每日統計-新帳號留存率'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_retention_user');
    }
}
