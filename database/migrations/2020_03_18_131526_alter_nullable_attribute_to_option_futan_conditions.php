<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNullableAttributeToOptionFutanConditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_futan_conditions', function (Blueprint $table) {
            $table->bigInteger('jouken_no')->nullable()->change();
            $table->integer('sex')->nullable()->change();
            $table->integer('honnin_kbn')->nullable()->change();
            $table->integer('yusen_kbn')->nullable()->change();
            $table->date('riyou_bgn_date')->nullable()->change();
            $table->date('riyou_end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_futan_conditions', function (Blueprint $table) {
            $table->bigInteger('jouken_no')->change();
            $table->integer('sex')->change();
            $table->integer('honnin_kbn')->change();
            $table->integer('yusen_kbn')->change();
            $table->date('riyou_bgn_date')->change();
            $table->date('riyou_end_date')->change();
        });
    }
}
