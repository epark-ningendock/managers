<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateCourseMetaTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->integer('hospital_id')->unsigned();
            $table->text('category_exam_name')->nullable();
            $table->text('category_disease_name')->nullable();
            $table->text('category_part_name')->nullable();
            $table->text('category_exam')->nullable();
            $table->text('category_disease')->nullable();
            $table->text('category_part')->nullable();
            $table->tinyInteger('meal_flg')->default(0);
            $table->tinyInteger('pear_flg')->default(0);
            $table->tinyInteger('female_doctor_flg')->default(0);

            // unique
            // foreign key
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
        Schema::dropIfExists('course_metas');
    }
}
