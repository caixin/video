<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'ads';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('type')->default(0)->comment('位置');
            $table->string('name', 50)->default('')->comment('名稱');
            $table->string('image', 500)->default('')->comment('圖片');
            $table->string('url', 500)->default('')->comment('連結');
            $table->dateTime('start_time')->default('1970-01-01 00:00:00')->comment('開始時間');
            $table->dateTime('end_time')->default('2030-12-31 00:00:00')->comment('結束時間');
            $table->smallInteger('sort')->default(0)->comment('排序');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->dateTime('updated_at')->default('1970-01-01 00:00:00')->comment('更新時間');
            $table->string('updated_by', 50)->default('')->comment('更新者');
        });

        DB::statement("ALTER TABLE `$tableName` comment '廣告列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
