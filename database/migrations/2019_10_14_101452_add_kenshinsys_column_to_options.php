<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKenshinsysColumnToOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->integer('kenshin_sys_course_no')->nullable()->after('status');
            $table->integer('kenshin_sys_option_no')->nullable()->after('kenshin_sys_course_no');
            $table->string('kenshin_sys_option_nm')->nullable()->after('kenshin_sys_option_no');
            $table->integer('kenshin_sys_option_age_kisan_kbn')->nullable()->after('kenshin_sys_option_nm');
            $table->date('kenshin_sys_option_age_kisan_date')->nullable()->after('kenshin_sys_option_age_kisan_kbn');
            $table->integer('kenshin_sys_flg')->default(0)->after('kenshin_sys_option_age_kisan_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('kenshin_sys_course_no');
            $table->dropColumn('kenshin_sys_option_no');
            $table->dropColumn('kenshin_sys_option_nm');
            $table->dropColumn('kenshin_sys_option_age_kisan_kbn');
            $table->dropColumn('kenshin_sys_option_age_kisan_date');
            $table->dropColumn('kenshin_sys_flg');
        });
    }
}
