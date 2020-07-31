<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToAutoUpdateDateColumnToCalendars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calendars', function (Blueprint $table) {
            $table->date('auto_update_start_date')->nullable()->after('auto_update_flg');
            $table->date('auto_update_end_date')->nullable()->after('auto_update_start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calendars', function (Blueprint $table) {
            $table->dropColumn('auto_update_start_date');
            $table->dropColumn('auto_update_end_date');
        });
    }
}
