<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReservationCountToCalendarDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendar_days', function (Blueprint $table) {
            $table->integer('reservation_count')->nullable()->after('reservation_frames');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calendar_days', function (Blueprint $table) {
            $table->dropColumn('reservation_count');
        });
    }
}
