<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMedicalSysIdColumnToKenshinSysDantaiInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kenshin_sys_dantai_infos', function (Blueprint $table) {
            $table->integer('medical_examination_system_id')->after('kenshin_sys_dantai_nm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kenshin_sys_dantai_infos', function (Blueprint $table) {
            $table->dropColumn('medical_examination_system_id');
        });
    }
}
