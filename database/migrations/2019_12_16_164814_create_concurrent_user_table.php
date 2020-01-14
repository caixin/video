<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcurrentUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'concurrent_user';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('per')->default(1)->comment('每幾分鐘');
            $table->datetime('minute_time')->default('1970-01-01 00:00:00')->comment('時間(每分鐘)');
            $table->integer('count')->default(0)->comment('人數');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->unique(['per','minute_time']);
        });

        DB::statement("ALTER TABLE `$tableName` comment '同時在線人數(CCU)'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concurrent_user');
    }
}
