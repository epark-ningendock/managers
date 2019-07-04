<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalTreatmentTimesTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_treatment_times', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('hospital_id');
            $table->string('start', 5)->default('-');
            $table->string('end', 5)->default('-');
            $table->tinyInteger('mon')->default(0)->nullable();
            $table->tinyInteger('tue')->default(0)->nullable();
            $table->tinyInteger('wed')->default(0)->nullable();
            $table->tinyInteger('thu')->default(0)->nullable();
            $table->tinyInteger('fri')->default(0)->nullable();
            $table->tinyInteger('sat')->default(0)->nullable();
            $table->tinyInteger('sun')->default(0)->nullable();
            $table->tinyInteger('hol')->default(0)->nullable();
            $table->char('status', 1)->default(1);
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
        Schema::dropIfExists('medical_treatment_times');
    }
}
