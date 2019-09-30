<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsiderationListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consideration_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('epark_member_id');
            $table->unsignedInteger('hospital_id')->nullable();
            $table->unsignedInteger('course_id')->nullable();
            $table->unsignedInteger('display_kbn');
            $table->char('status', 1);
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
        Schema::dropIfExists('consideration_lists');
    }
}
