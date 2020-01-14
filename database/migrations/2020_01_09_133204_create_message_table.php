<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'message';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->default(0)->comment('用戶ID');
            $table->tinyInteger('type')->default(1)->comment('問題類型');
            $table->text('content')->comment('問題內容');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->dateTime('updated_at')->default('1970-01-01 00:00:00')->comment('更新時間');
            $table->string('updated_by', 50)->default('')->comment('更新者');
            $table->index(['uid']);
            $table->index(['type']);
        });

        DB::statement("ALTER TABLE `$tableName` comment '留言板'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}
