<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefectureRailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefecture_rail', function (Blueprint $table) {
            $table->unsignedInteger('prefecture_id');
            $table->unsignedInteger('rail_id');
            $table->timestamps();

            $table->primary(['rail_id', 'prefecture_id']);

            $table->foreign('rail_id')->references('id')->on('rails');
            $table->foreign('prefecture_id')->references('id')->on('prefectures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prefecture_rail');
    }
}
