<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateCourseDetailsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->integer('major_classification_id')->unsigned();
            $table->foreign('major_classification_id')->references('id')->on('major_classifications');
            $table->integer('middle_classification_id')->unsigned();
            $table->foreign('middle_classification_id')->references('id')->on('middle_classifications');
            $table->integer('minor_classification_id')->unsigned();
            $table->foreign('minor_classification_id')->references('id')->on('minor_classifications');
            $table->tinyInteger('select_status')->nullable();
            $table->text('inputstring')->nullable();
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
        Schema::dropIfExists('course_details');
    }
}
