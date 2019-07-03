<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateReservationOptionsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned()->nullable();
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->integer('option_id')->unsigned()->nullable();
            $table->foreign('option_id')->references('id')->on('options');
            $table->integer('option_price');
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
        Schema::dropIfExists('reservation_options');
    }
}
