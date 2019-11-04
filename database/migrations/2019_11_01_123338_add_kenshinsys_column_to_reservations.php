<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKenshinsysColumnToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->integer('medical_examination_system_id')->nullable()->after('payment_date');
            $table->foreign('medical_examination_system_id')->references('id')->on('medical_examination_systems');
            $table->integer('kenshin_sys_yoyaku_no')->nullable()->after('medical_examination_system_id');
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
            $table->dropForeign(['medical_examination_system_id']);
            $table->dropColumn('medical_examination_system_id');
            $table->dropColumn('kenshin_sys_yoyaku_no');
        });
    }
}
