<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class FromReceptionEmailSettingsToHospitalEmailSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('reception_email_settings', 'hospital_email_settings');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('hospital_email_settings')) {
            Schema::rename('hospital_email_settings', 'reception_email_settings');
        }
    }
}