<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseColumnToHospitalMetas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_metas', function (Blueprint $table) {
            $table->text('course_name')->nullable()->after('dedicate_floor_flg');
            $table->text('category_exam_name')->nullable()->after('course_name');
            $table->text('category_disease_name')->nullable()->after('category_exam_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_metas', function (Blueprint $table) {
            $table->dropColumn('course_name');
            $table->dropColumn('category_exam_name');
            $table->dropColumn('category_disease_name');
        });
    }
}
