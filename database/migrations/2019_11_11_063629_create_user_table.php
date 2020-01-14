<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'user';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 50)->default('')->comment('Token')->unique();
            $table->string('username')->default('')->comment('用戶名')->unique();
            $table->string('password')->default('')->comment('密碼');
            $table->integer('money')->default(0)->comment('貨幣');
            $table->integer('referrer')->default(0)->comment('推薦人');
            $table->tinyInteger('status')->default(0)->comment('狀態 0:發送簡訊未註冊 1:正式用戶 1:封鎖用戶');
            $table->text('create_ua')->comment('註冊UA資訊');
            $table->string('create_ip', 50)->default('')->comment('註冊IP');
            $table->json('create_ip_info')->comment('註冊IP資訊');
            $table->string('login_ip', 50)->default('')->comment('最後登入IP');
            $table->dateTime('login_time')->default('1970-01-01 00:00:00')->comment('最後登入時間');
            $table->dateTime('active_time')->default('1970-01-01 00:00:00')->comment('最後活動時間');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->dateTime('updated_at')->default('1970-01-01 00:00:00')->comment('更新時間');
            $table->string('updated_by', 50)->default('')->comment('更新者');
            $table->softDeletes();
            $table->index('created_at');
        });

        DB::statement("ALTER TABLE `$tableName` comment '用戶列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
