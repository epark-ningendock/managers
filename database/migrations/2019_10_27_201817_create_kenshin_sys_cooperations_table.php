<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateKenshinSysCooperationsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kenshin_sys_cooperations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('medical_examination_system_id')->unsigned();
            $table->foreign('medical_examination_system_id')->references('id')->on('medical_examination_systems');
            $table->char('app_kbn', 1);
            $table->string('api_url', 128);
            $table->char('partner_code', 10);
            $table->char('hash_key', 11);
            $table->string('subscription_key', 64);
            $table->string('ip', 64);
            $this->addCommonColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kenshin_sys_cooperations');
    }
}
