<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateKenshinSysCoursesTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kenshin_sys_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('kenshin_sys_hospital_id')->unsigned();
            $table->bigInteger('kenshin_sys_dantai_info_id');
            $table->bigInteger('kenshin_sys_dantai_no');
            $table->bigInteger('kenshin_sys_course_no');
            $table->string('kenshin_sys_course_name', 100);
            $table->integer('kenshin_sys_course_kingaku');
            $table->date('kenshin_sys_riyou_bgn_date');
            $table->date('kenshin_sys_riyou_end_date');
            $table->integer('kenshin_sys_course_age_kisan_kbn');
            $table->char('kenshin_sys_course_age_kisan_date', 4)->nullable();
            $table->char('status', 1)->default('1');
            $this->addCommonColumns($table);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kenshin_sys_courses');
    }
}
