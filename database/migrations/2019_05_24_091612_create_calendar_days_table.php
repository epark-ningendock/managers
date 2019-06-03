<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateCalendarDaysTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_days', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->tinyInteger('is_holiday')->unsigned();
            $table->tinyInteger('is_reservation_acceptance')->unsigned();
            $table->integer('reservation_flames')->nullable();
            $table->integer('calendar_id')->unsigned();
            $table->foreign('calendar_id')->references('id')->on('calendars');
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
        Schema::dropIfExists('calendar_days');
    }
}
