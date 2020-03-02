<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccessColumnToHospitalMetas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_metas', function (Blueprint $table) {
            $table->string('rail1')->nullable()->after('area_station');
            $table->string('station1')->nullable()->after('rail1');
            $table->string('access1')->nullable()->after('station1');
            $table->string('rail2')->nullable()->after('access1');
            $table->string('station2')->nullable()->after('rail2');
            $table->string('access2')->nullable()->after('station2');
            $table->string('rail3')->nullable()->after('access2');
            $table->string('station3')->nullable()->after('rail3');
            $table->string('access3')->nullable()->after('station3');
            $table->string('rail4')->nullable()->after('access3');
            $table->string('station4')->nullable()->after('rail4');
            $table->string('access4')->nullable()->after('station4');
            $table->string('rail5')->nullable()->after('access4');
            $table->string('station5')->nullable()->after('rail5');
            $table->string('access5')->nullable()->after('station5');
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
            $table->dropColumn('rail1');
            $table->dropColumn('station1');
            $table->dropColumn('access1');
            $table->dropColumn('rail2');
            $table->dropColumn('station2');
            $table->dropColumn('access2');
            $table->dropColumn('rail3');
            $table->dropColumn('station3');
            $table->dropColumn('access3');
            $table->dropColumn('rail4');
            $table->dropColumn('station4');
            $table->dropColumn('access4');
            $table->dropColumn('rail5');
            $table->dropColumn('station5');
            $table->dropColumn('access5');
        });
    }
}
