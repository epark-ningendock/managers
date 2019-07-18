<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationAnswersTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reservation_id');
            $table->unsignedInteger('course_id');
            $table->integer('course_question_id');
            $table->string('question_title')->nullable();
            $table->string('question_answer01')->nullable();
            $table->string('question_answer02')->nullable();
            $table->string('question_answer03')->nullable();
            $table->string('question_answer04')->nullable();
            $table->string('question_answer05')->nullable();
            $table->string('question_answer06')->nullable();
            $table->string('question_answer07')->nullable();
            $table->string('question_answer08')->nullable();
            $table->string('question_answer09')->nullable();
            $table->string('question_answer10')->nullable();
            $table->char('answer01', 1)->default(0);
            $table->char('answer02', 1)->default(0);
            $table->char('answer03', 1)->default(0);
            $table->char('answer04', 1)->default(0);
            $table->char('answer05', 1)->default(0);
            $table->char('answer06', 1)->default(0);
            $table->char('answer07', 1)->default(0);
            $table->char('answer08', 1)->default(0);
            $table->char('answer09', 1)->default(0);
            $table->char('answer10', 1)->default(0);

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
        Schema::dropIfExists('reservation_answers');
    }
}
