<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropContractPlanIdAndDropToRenameBillingMonthForFromColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn(['contract_plan_id', 'to']);
            $table->renameColumn('from', 'billing_month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billings', function (Blueprint $table) {
	        $table->integer('contract_plan_id');
	        $table->string('to');
	        $table->renameColumn('billing_month', 'from');
        });
    }
}
