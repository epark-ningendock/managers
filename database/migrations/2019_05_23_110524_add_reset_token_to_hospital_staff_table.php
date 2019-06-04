<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResetTokenToHospitalStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_staffs', function (Blueprint $table) {
            $table->string('reset_token_digest')->nullable();
            $table->dateTime('reset_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_staffs', function (Blueprint $table) {
            $table->dropColumn('reset_token_digest');
            $table->dropColumn('reset_sent_at');
        });
    }
}
