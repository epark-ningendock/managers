<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKenshinsysHospitalIdColumnToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->bigInteger('kenshin_sys_hospital_id')->nullable()->after('kenshin_sys_yoyaku_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('kenshin_sys_hospital_id');
        });
    }
}
