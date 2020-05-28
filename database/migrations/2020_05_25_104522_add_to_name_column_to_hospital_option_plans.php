<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToNameColumnToHospitalOptionPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_option_plans', function (Blueprint $table) {
            $table->string('name')->nullable()->after('option_plan_id');
            $table->integer('initial_cost')->nullable()->after('price');
            $table->integer('billing_flg')->nullable()->after('to');
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
            $table->dropColumn('name');
            $table->dropColumn('initial_cost');
            $table->dropColumn('billing_flg');
        });
    }
}
