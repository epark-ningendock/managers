<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeContractInfomationTableColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_informations', function (Blueprint $table) {
            $table->integer('contract_plan_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_informations', function (Blueprint $table) {
            $table->char('contract_plan_id', 4)->change();
        });
    }
}
