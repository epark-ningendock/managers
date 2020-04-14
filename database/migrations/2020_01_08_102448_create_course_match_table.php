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
            $table->increments('id');
            $table->integer('course_id');
            $table->bigInteger('kenshin_sys_course_id');
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
        Schema::dropIfExists('course_match');
    }
}
