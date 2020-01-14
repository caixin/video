<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'admin_login_log';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adminid')->default(0)->comment('adminid');
            $table->string('ip', 50)->default('')->comment('登入IP');
            $table->json('ip_info')->comment('IP資訊');
            $table->tinyInteger('status')->default(1)->comment('狀態 0:失敗 1:成功');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->index('created_at');
        });

        DB::statement("ALTER TABLE `$tableName` comment '系統帳號登入LOG'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_login_log');
    }
}
