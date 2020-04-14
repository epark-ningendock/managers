<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOptionNameColumnToReservationKenshinSysOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservation_kenshin_sys_options', function (Blueprint $table) {
            $table->bigInteger('kenshin_sys_option_no')->nullable()->after('kenshin_sys_option_id');
            $table->string('kenshin_sys_option_name')->nullable()->after('kenshin_sys_option_no');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservation_kenshin_sys_options', function (Blueprint $table) {
            $table->dropColumn('kenshin_sys_option_no');
            $table->dropColumn('kenshin_sys_option_name');
        });
    }
}
