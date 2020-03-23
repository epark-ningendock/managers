<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterKenshinSysCourseNoInKenshinSysCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kenshin_sys_courses', function (Blueprint $table) {
            $table->string('kenshin_sys_course_no',30)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kenshin_sys_courses', function (Blueprint $table) {
            $table->bigInteger('kenshin_sys_course_no')->change();
        });
    }
}
