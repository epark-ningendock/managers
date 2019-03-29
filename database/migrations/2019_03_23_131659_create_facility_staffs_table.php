<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('email', 256)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 256);
            $table->rememberToken();
            $table->unsignedInteger('author', false, 10)->nullable();
            $table->unsignedInteger('changer', false, 10)->nullable();
            $table->unsignedInteger('remover', false, 10)->nullable();
            $table->softDeletes(); 
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
        Schema::dropIfExists('facility_staffs');
    }
}
