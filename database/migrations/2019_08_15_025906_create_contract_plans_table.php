<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->char('plan_code',2);
            $table->string('plan_name', 100);
            $table->unsignedTinyInteger('fee_rate');
            $table->unsignedBigInteger('monthly_contract_fee');
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
        Schema::dropIfExists('contract_plans');
    }
}
