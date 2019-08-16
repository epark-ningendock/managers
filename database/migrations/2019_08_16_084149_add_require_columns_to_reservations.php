<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequireColumnsToReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('applicant_name', 130)->nullable();
            $table->string('applicant_name_kana', 130)->nullable();
            $table->string('applicant_tel', 11)->nullable();
            $table->integer('fee_rate')->nullable();
            $table->integer('fee')->nullable();
            $table->tinyInteger('is_free_hp_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('applicant_name');
            $table->dropColumn('applicant_name_kana');
            $table->dropColumn('applicant_tel');
            $table->dropColumn('fee_rate');
            $table->dropColumn('fee');
            $table->dropColumn('is_free_hp_link');
        });
    }
}
