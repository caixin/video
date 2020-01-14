<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'video';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 50)->comment('視頻Keyword')->unique();
            $table->string('name')->default('')->comment('片名');
            $table->date('publish')->default('1970-01-01')->comment('發行日期');
            $table->string('actors')->default('')->comment('女優(逗號分隔)');
            $table->string('tags')->default('')->comment('Tags(逗號分隔)');
            $table->string('pic_b')->default('')->comment('封面圖-大');
            $table->string('pic_s')->default('')->comment('封面圖-小');
            $table->string('url')->default('')->comment('視頻連結');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->dateTime('updated_at')->default('1970-01-01 00:00:00')->comment('更新時間');
            $table->string('updated_by', 50)->default('')->comment('更新者');
            $table->index('created_at');
        });

        DB::statement("ALTER TABLE `$tableName` comment '影片列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video');
    }
}
