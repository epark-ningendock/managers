<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateCourseFutanConditionsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_futan_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('kenshin_sys_course_id')->unsigned();
            $table->bigInteger('jouken_no');
            $table->integer('sex');
            $table->integer('honnin_kbn');
            $table->integer('futan_kingaku');
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
        Schema::dropIfExists('course_futan_conditions');
    }
}
