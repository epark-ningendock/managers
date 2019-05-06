<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contractor_name_kana', 50);
            $table->string('contractor_name', 50);
            $table->dateTime('application_date');
            $table->dateTime('billing_start_date');
            $table->dateTime('cancellation_date')->nullable();
            $table->string('representative_name_kana', 50);
            $table->string('representative_name', 50);
            $table->string('postcode', 30)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('tel', 30);
            $table->string('fax', 30)->nullable();
            $table->string('email', 255)->unique();
            $table->string('karada_dog_id', 20)->nullable();
            $table->string('code', 20)->nullable();
            $table->string('old_karada_dog_id', 20);
            $table->integer('hospital_staff_id')->nullable();

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
        Schema::dropIfExists('contract_informations');
    }
}
