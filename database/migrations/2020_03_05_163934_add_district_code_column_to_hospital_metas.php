<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistrictCodeColumnToHospitalMetas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_metas', function (Blueprint $table) {
            $table->char('district_code', 7)->nullable()->after('hospital_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_metas', function (Blueprint $table) {
            $table->dropColumn('district_code');
        });
    }
}
