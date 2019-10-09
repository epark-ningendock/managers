<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvertedIdStringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('converted_id_strings', function (Blueprint $table) {
            $table->string('table_name');
            $table->string('old_id');
            $table->string('hospital_no');
            $table->unsignedBigInteger('new_id');
            $table->timestamps();

            $table->unique(['table_name', 'old_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('converted_id_strings');
    }
}
