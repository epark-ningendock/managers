<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHospitalIdToHospitalStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_staffs', function (Blueprint $table) {
            $table->integer('hospital_id')->unsigned();
            $table->foreign('hospital_id')->references('id')->on('hospitals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_staffs', function (Blueprint $table) {
            $table->dropForeign('hospital_staffs_hospital_id_foreign');
            $table->dropColumn('hospital_id');
        });
    }
}
