<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommonColumnsToContractPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_plans', function (Blueprint $table) {
            $table->char('status', 1)->default('1');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_plans', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('status');
        });
    }
}
