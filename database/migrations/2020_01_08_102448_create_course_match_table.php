<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateCourseMatchTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_match', function (Blueprint $table) {
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('kenshin_sys_course_id');
            $table->timestamps();

            $table->primary(['course_id', 'kenshin_sys_course_id']);

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('kenshin_sys_course_id')->references('id')->on('kenshin_sys_courses');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_match');
    }
}
