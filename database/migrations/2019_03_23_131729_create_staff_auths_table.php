<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_auths', function (Blueprint $table) {
            $table->increments('id');
            $table->char('is_hospital', 1)->default(0);
            $table->char('is_staff', 1)->default(0);
            $table->char('is_item_category', 1)->default(0);
            $table->char('is_invoice', 1)->default(0);
            $table->char('is_pre_account', 1)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_auths');
    }
}
