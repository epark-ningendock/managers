<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvertedIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('converted_ids', function (Blueprint $table) {
            $table->string('table_name');
            $table->unsignedBigInteger('old_id');
            $table->unsignedBigInteger('new_id');
            $table->timestamps();

            $table->unique(['table_name' , 'old_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('converted_ids');
    }
}
