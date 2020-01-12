<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateCourseWakusTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kenshin_sys_course_wakus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kenshin_sys_course_id')->unsigned();
            $table->integer('kenshin_sys_course_no');
            $table->integer('year_month');
            $table->integer('waku_kbn');
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
        Schema::dropIfExists('kenshin_sys_course_wakus');
    }
}
