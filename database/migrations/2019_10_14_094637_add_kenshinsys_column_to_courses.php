<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKenshinsysColumnToCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('kenshin_sys_dantai_info_id')->nullable()->after('auto_calc_application');
            $table->foreign('kenshin_sys_dantai_info_id')->references('id')->on('kenshin_sys_dantai_infos');
            $table->integer('kenshin_sys_course_no')->nullable()->after('kenshin_sys_dantai_info_id');
            $table->integer('kenshin_sys_course_kingaku')->nullable()->after('kenshin_sys_course_no');
            $table->date('kenshin_sys_riyou_bgn_date')->nullable()->after('kenshin_sys_course_kingaku');
            $table->date('kenshin_sys_riyou_end_date')->nullable()->after('kenshin_sys_riyou_bgn_date');
            $table->integer('kenshin_sys_course_age_kisan_kbn')->nullable()->after('kenshin_sys_riyou_end_date');
            $table->date('kenshin_sys_course_age_kisan_date')->nullable()->after('kenshin_sys_course_age_kisan_kbn');
            $table->integer('kenshin_sys_flg')->default(0)->after('kenshin_sys_course_age_kisan_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_dantai_info_id_foreign');
            $table->dropColumn('kenshin_sys_dantai_info_id');
            $table->dropColumn('kenshin_sys_course_no');
            $table->dropColumn('kenshin_sys_course_kingaku');
            $table->dropColumn('kenshin_sys_riyou_bgn_date');
            $table->dropColumn('kenshin_sys_riyou_end_date');
            $table->dropColumn('kenshin_sys_course_age_kisan_kbn');
            $table->dropColumn('kenshin_sys_course_age_kisan_date');
            $table->dropColumn('kenshin_sys_flg');
        });
    }
}
