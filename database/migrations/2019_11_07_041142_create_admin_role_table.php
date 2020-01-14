<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'admin_role';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('')->comment('角色名稱');
            $table->json('allow_nav')->comment('導航權限');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->dateTime('updated_at')->default('1970-01-01 00:00:00')->comment('更新時間');
            $table->string('updated_by', 50)->default('')->comment('更新者');
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `$tableName` comment '系統帳號角色權限'");
        
        DB::table($tableName)->insert([
            'id'         => 1,
            'name'       => '超级管理者',
            'allow_nav'  => json_encode([]),
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
        Schema::dropIfExists('admin_role');
    }
}
