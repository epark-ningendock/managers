<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJoukenNoColumnToOptionFutanConditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_futan_conditions', function (Blueprint $table) {
            $table->bigInteger('jouken_no')->nullable()->after('kenshin_sys_option_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_futan_conditions', function (Blueprint $table) {
            $table->dropColumn('jouken_no');
        });
    }
}
