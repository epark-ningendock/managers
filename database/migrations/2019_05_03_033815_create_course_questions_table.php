<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateCourseQuestionsTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->tinyInteger('question_number')->unsigned();
            $table->tinyInteger('is_question')->unsigned()->default(0);
            $table->string('question_title', 100)->nullable();
            $table->text('answer01')->nullable();
            $table->text('answer02')->nullable();
            $table->text('answer03')->nullable();
            $table->text('answer04')->nullable();
            $table->text('answer05')->nullable();
            $table->text('answer06')->nullable();
            $table->text('answer07')->nullable();
            $table->text('answer08')->nullable();
            $table->text('answer09')->nullable();
            $table->text('answer10')->nullable();
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
        Schema::dropIfExists('course_questions');
    }
}
