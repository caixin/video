<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminActionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'admin_action_log';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adminid')->default(0)->comment('adminid');
            $table->string('route', 100)->default('')->comment('路由');
            $table->text('message')->comment('操作訊息');
            $table->text('sql')->comment('SQL指令');
            $table->string('ip', 50)->default('')->comment('登入IP');
            $table->tinyInteger('status')->default(1)->comment('狀態 0:失敗 1:成功');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->index('created_at');
        });

        DB::statement("ALTER TABLE `$tableName` comment '系統帳號操作LOG'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_action_log');
    }
}
