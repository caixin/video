<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMoneyLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'user_money_log';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用戶ID');
            $table->smallInteger('type')->comment('類型');
            $table->string('video_keyword', 50)->default('')->comment('視頻ID');
            $table->integer('money_before')->default(0)->comment('變動前餘額');
            $table->integer('money_add')->default(0)->comment('變動金額');
            $table->integer('money_after')->default(0)->comment('變動後餘額');
            $table->string('description')->default('')->comment('描述');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->index('uid');
            $table->index(['created_at','type']);
        });

        DB::statement("ALTER TABLE `$tableName` comment '用戶餘額變動LOG'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_money_log');
    }
}
