<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateHokenInfosTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kenshin_sys_hoken_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kenshin_sys_dantai_info_id')->unsigned();
            $table->foreign('kenshin_sys_dantai_info_id')->references('id')->on('kenshin_sys_dantai_infos');
            $table->integer('hoken_no');
            $table->integer('hoken_kigou');
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
        Schema::dropIfExists('kenshin_sys_hoken_infos');
    }
}
