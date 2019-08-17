<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BillingEmailFlagToReceptionEmailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reception_email_settings', function (Blueprint $table) {
            $table->tinyInteger('billing_email_flg')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reception_email_settings', function (Blueprint $table) {
            $table->dropColumn('billing_email_flg');
        });
    }
}
