<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingEmail4ColumnToHospitalEmailSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_email_settings', function (Blueprint $table) {
            $table->string('billing_email4')->nullable()->after('billing_email3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_email_settings', function (Blueprint $table) {
            $table->dropColumn('billing_email4');
        });
    }
}
