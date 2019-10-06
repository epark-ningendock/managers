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
            $table->char('contract_plan_id', 4);
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
        Schema::dropIfExists('hospital_plans');
    }
}
