<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateHospitalOptionPlansTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_option_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id');
            $table->integer('option_plan_id');
            $table->date('from');
            $table->date('to')->nullable();

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
        Schema::dropIfExists('hospital_option_plans');
    }
}
