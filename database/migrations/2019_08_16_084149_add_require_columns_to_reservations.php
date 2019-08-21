<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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
            $table->tinyInteger('is_free_hp_link')->default(0);
            $table->tinyInteger('is_health_insurance')->default(0);
            $table->boolean('is_representative')->default(true)->change();
            $table->dropColumn('timezone_pattern_id');
            $table->dropColumn('timezone_id');
            $table->dropColumn('order');
            $table->string('payment_method')->nullable()->change();
            $table->string('payment_status')->nullable()->change();
            $table->string('trade_id')->nullable()->change();
        });

        // for unsupported change column types
        DB::statement('ALTER TABLE reservations MODIFY terminal_type TINYINT DEFAULT 1');
        DB::statement('ALTER TABLE reservations MODIFY mail_type CHAR(1)');
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
            $table->dropColumn('is_health_insurance');
            $table->boolean('is_representative')->change();
            $table->string('timezone_pattern_id');
            $table->string('timezone_id');
            $table->string('order');
            $table->string('payment_method')->change();
            $table->string('payment_status')->change();
            $table->string('trade_id')->change();
        });
    }
}
