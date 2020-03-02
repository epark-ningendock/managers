<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayPerUsePriceColumnToHospitalOptionPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_option_plans', function (Blueprint $table) {
            $table->integer('pay_per_use_price')->nullable()->after('option_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_option_plans', function (Blueprint $table) {
            $table->dropColumn('pay_per_use_price');
        });
    }
}
