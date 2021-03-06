<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateDantaiInfosTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kenshin_sys_dantai_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('kenshin_sys_hospital_id')->unsigned();
            $table->bigInteger('kenshin_sys_dantai_no');
            $table->string('kenshin_sys_dantai_nm', 100);
            $table->char('status', 1)->default('1');
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
        Schema::dropIfExists('kenshin_sys_dantai_infos');
    }
}
