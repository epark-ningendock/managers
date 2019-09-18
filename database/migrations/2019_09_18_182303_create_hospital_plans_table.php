<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalPlansTable extends Migration
{
	use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_plans', function (Blueprint $table) {
	        $table->increments('id');
	        $table->integer('hospital_id');
	        $table->integer('contract_plan_id');
	        $table->string('from');
	        $table->string('to')->nullable();
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
        Schema::dropIfExists('hospital_plans');
    }
}
