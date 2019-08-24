<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRailStationTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rail_station', function (Blueprint $table) {
            $table->unsignedInteger('rail_id');
            $table->unsignedInteger('station_id');
            $table->timestamps();

            $table->primary(['rail_id', 'station_id']);

            $table->foreign('rail_id')->references('id')->on('rails');
            $table->foreign('station_id')->references('id')->on('stations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rail_station');
    }
}
