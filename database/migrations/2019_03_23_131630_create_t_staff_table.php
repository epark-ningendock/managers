<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_staff', function (Blueprint $table) {
            $table->increments('no', 10)->unsigned();
            $table->string('name', 50);
            $table->string('id', 100);
            $table->string('password', 256);
            $table->char('authority', 1);
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
        Schema::dropIfExists('t_staff');
    }
}
