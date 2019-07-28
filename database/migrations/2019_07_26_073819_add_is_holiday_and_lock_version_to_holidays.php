<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsHolidayAndLockVersionToHolidays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->integer('lock_version')->unsigned()->default(1);
            $table->integer('is_holiday')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('lock_version');
            $table->dropColumn('is_holiday');
        });
    }
}
