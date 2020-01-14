<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'admin';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 50)->default('')->comment('Token');
            $table->string('username')->default('')->comment('用戶名')->unique();
            $table->string('password')->default('')->comment('密碼');
            $table->smallInteger('roleid')->default(0)->comment('角色ID');
            $table->string('login_ip', 50)->default('')->comment('登入IP');
            $table->dateTime('login_time')->default('1970-01-01 00:00:00')->comment('登入時間');
            $table->integer('login_count')->default(0)->comment('登入次數');
            $table->tinyInteger('status')->default(1)->comment('狀態 1:開啟 0:關閉');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->dateTime('updated_at')->default('1970-01-01 00:00:00')->comment('更新時間');
            $table->string('updated_by', 50)->default('')->comment('更新者');
            $table->index('created_at');
        });

        DB::statement("ALTER TABLE `$tableName` comment '系統帳號'");
        
        DB::table($tableName)->insert([
            'id'        => 1,
            'username'  => 'admin',
            'password'  => Hash::make('ji3g4go6'),
            'roleid'    => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
