<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNullableAttributeToCourseWakus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kenshin_sys_course_wakus', function (Blueprint $table) {
            $table->bigInteger('jouken_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kenshin_sys_course_wakus', function (Blueprint $table) {
            $table->bigInteger('jouken_no')->change();

        });
    }
}
