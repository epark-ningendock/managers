<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJoukenNoInOptionFutanConditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_futan_conditions', function (Blueprint $table) {
            $table->string('jouken_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_futan_conditions', function (Blueprint $table) {
            $table->bigInteger('jouken_no')->change();
        });
    }
}
