<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNendoBgnDateColumnToKenshinSysCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kenshin_sys_courses', function (Blueprint $table) {
            $table->char('kenshin_sys_nendo_bgn_date', 4)->nullable()->after('kenshin_sys_course_age_kisan_date');
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
            $table->dropColumn('kenshin_sys_nendo_bgn_date');
        });
    }
}
