<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileLocationNoToHospitalCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_categories', function (Blueprint $table) {
            $table->integer('file_location_no')->nullable()->after('order2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_categories', function (Blueprint $table) {
            $table->dropColumn('file_location_no');
        });
    }
}
