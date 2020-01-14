<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'domain_setting';

        Schema::create($tableName, function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain')->default('')->comment('網域');
            $table->string('title')->default(0)->comment('標題');
            $table->string('keyword')->default(0)->comment('關鍵字');
            $table->string('description')->default(0)->comment('描述');
            $table->text('baidu')->comment('百度統計代碼');
            $table->dateTime('created_at')->default('1970-01-01 00:00:00')->comment('建檔時間');
            $table->string('created_by', 50)->default('')->comment('新增者');
            $table->dateTime('updated_at')->default('1970-01-01 00:00:00')->comment('更新時間');
            $table->string('updated_by', 50)->default('')->comment('更新者');
            $table->unique(['domain']);
        });

        DB::statement("ALTER TABLE `$tableName` comment '網域設定'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domain_setting');
    }
}
