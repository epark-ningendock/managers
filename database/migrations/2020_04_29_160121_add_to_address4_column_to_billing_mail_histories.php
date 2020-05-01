<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToAddress4ColumnToBillingMailHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_mail_histories', function (Blueprint $table) {
            $table->string('to_address4')->nullable()->after('to_address3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_mail_histories', function (Blueprint $table) {
            $table->dropColumn('to_address4');
        });
    }
}
