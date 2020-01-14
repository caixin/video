<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'daily_user';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')->default('1970-01-01')->comment('日期');
            $table->integer('uid')->comment('用戶ID');
            $table->integer('login')->default(1)->comment('登入次數');
            $table->integer('point')->default(0)->comment('花費點數');
            $table->integer('consecutive')->default(1)->comment('連續登入天數');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->unique(['date','uid']);
            $table->index('uid');
        });

        DB::statement("ALTER TABLE `$tableName` comment '每日用戶統計'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_user');
    }
}
