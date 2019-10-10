<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequireColumnsInContractInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_informations', function (Blueprint $table) {
            $table->integer('lock_version')->unsigned()->default(1);
            $table->dropColumn('karada_dog_id');
            $table->dropColumn('old_karada_dog_id');
            $table->dropColumn('hospital_staff_id');
            $table->string('email', 255)->nullable();
            $table->string('property_no', 20)->nullable();
//            $table->integer('contract_plan_id')->unsigned()->nullable();
            $table->char('contract_plan_id', 4)->nullable();
//            $table->foreign('contract_plan_id')->references('plan_code')->on('contract_plans');
            $table->integer('hospital_id')->unsigned()->nullable();
            $table->foreign('hospital_id')->references('id')->on('hospitals');
            $table->datetime('service_start_date')->nullable();
            $table->datetime('service_end_date')->nullable();
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
            $table->dropColumn('lock_version');
            $table->string('karada_dog_id', 20)->nullable();
            $table->string('old_karada_dog_id', 20);
            $table->integer('hospital_staff_id')->nullable();
            $table->dropColumn('email');
            $table->dropColumn('property_no');
            $table->dropForeign(['contract_plan_id']);
            $table->dropColumn(['contract_plan_id']);
            $table->dropForeign(['hospital_id']);
            $table->dropColumn('hospital_id');
            $table->dropColumn('service_start_date');
            $table->dropColumn('service_end_date');
        });
    }
}
