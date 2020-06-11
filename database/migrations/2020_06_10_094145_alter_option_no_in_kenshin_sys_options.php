<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOptionNoInKenshinSysOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kenshin_sys_options', function (Blueprint $table) {
            $table->string('kenshin_sys_option_no')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kenshin_sys_options', function (Blueprint $table) {
            $table->bigInteger('kenshin_sys_option_no')->change();
        });
    }
}
