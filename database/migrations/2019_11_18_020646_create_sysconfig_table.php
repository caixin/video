<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysconfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'sysconfig';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('skey')->default('')->comment('關鍵字')->unique();
            $table->text('svalue')->comment('值');
            $table->string('info')->default('')->comment('說明');
            $table->smallInteger('groupid')->default(0)->comment('群組');
            $table->smallInteger('type')->default(0)->comment('變數類型');
            $table->smallInteger('sort')->default(0)->comment('排序');
        });

        DB::statement("ALTER TABLE `$tableName` comment '系統參數'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sysconfig');
    }
}
