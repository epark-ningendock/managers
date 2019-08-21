<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BillingFaxNumberToReceptionEmailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reception_email_settings', function (Blueprint $table) {
            $table->string('billing_fax_number')->nullable();
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
            $table->dropColumn('billing_fax_number');
        });
    }
}
