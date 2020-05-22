<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToPriceColumnToHospitalOptionPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_option_plans', function (Blueprint $table) {
            $table->integer('price')->nullable()->after('option_plan_id');
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
            $table->dropColumn('price');
        });
    }
}
