<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateAvailabilsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availabils', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_no');
            $table->integer('course_no');
            $table->integer('reservation_dt');
            $table->integer('line_id');
            $table->integer('appoint_number');
            $table->integer('reservation_frames');

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
        Schema::dropIfExists('availabils');
    }
}
