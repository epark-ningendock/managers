<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKenshinsysTimeColumnToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('kenshin_sys_start_time')->nullable()->after('kenshin_sys_yoyaku_no');
            $table->string('kenshin_sys_end_time')->nullable()->after('kenshin_sys_start_time');
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
            $table->dropColumn('kenshin_sys_start_time');
            $table->dropColumn('kenshin_sys_end_time');
        });
    }
}
