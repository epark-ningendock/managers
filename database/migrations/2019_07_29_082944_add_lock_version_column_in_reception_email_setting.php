<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLockVersionColumnInReceptionEmailSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reception_email_settings', function (Blueprint $table) {
            $table->integer('lock_version')->unsigned()->default(1);
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
            $table->dropColumn('lock_version');
        });
    }
}
