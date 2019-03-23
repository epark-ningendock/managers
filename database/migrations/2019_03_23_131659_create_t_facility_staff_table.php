<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTFacilityStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_facility_staff', function (Blueprint $table) {
            $table->increments('no', 10)->unsigned();
            $table->string('name', 50)->nullable();
            $table->string('id', 100)->nullable();
            $table->string('password', 256)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_facility_staff');
    }
}
