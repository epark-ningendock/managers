<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMemoInReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->text('todays_memo')->nullable()->change();
            $table->text('reservation_memo')->nullable()->change();
            $table->text('internal_memo')->nullable()->change();
            $table->text('user_message')->nullable()->change();
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
            $table->string('todays_memo')->change();
            $table->string('reservation_memo')->change();
            $table->string('internal_memo')->change();
            $table->string('user_message')->change();
        });
    }
}
