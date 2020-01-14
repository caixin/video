<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminNavTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'admin_nav';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->default(0)->comment('上層ID');
            $table->string('icon', 50)->default('')->comment('ICON');
            $table->string('name', 50)->default('')->comment('導航名稱');
            $table->string('route', 100)->default('')->comment('主路由');
            $table->string('route1', 100)->default('')->comment('次路由1');
            $table->string('route2', 100)->default('')->comment('次路由2');
            $table->string('path', 100)->default('')->comment('階層路徑');
            $table->smallInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('狀態 1:開啟 0:關閉');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->dateTime('updated_at')->default('1970-01-01 00:00:00')->comment('更新時間');
            $table->string('updated_by', 50)->default('')->comment('更新者');
        });

        DB::statement("ALTER TABLE `$tableName` comment '導航列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_nav');
    }
}
