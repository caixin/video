<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'user_login_log';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->comment('用戶ID');
            $table->string('ip', 50)->default('')->comment('登入IP');
            $table->json('ip_info')->comment('IP資訊');
            $table->text('ua')->comment('User Agent');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->index('created_at');
        });

        DB::statement("ALTER TABLE `$tableName` comment '用戶登入LOG'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_login_log');
    }
}
