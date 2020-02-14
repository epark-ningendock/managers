<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKenshinsysWakuColumnToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->bigInteger('kenshin_sys_yoyaku_waku_no')->nullable()->after('kenshin_sys_end_time');
            $table->integer('kenshin_sys_yoyaku_waku_seq')->nullable()->after('kenshin_sys_yoyaku_waku_no');
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
            $table->dropColumn('kenshin_sys_yoyaku_waku_no');
            $table->dropColumn('kenshin_sys_yoyaku_waku_seq');
        });
    }
}
