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
            $table->tinyInteger('is_hospital', false, 1)->default(0);
            $table->tinyInteger('is_staff', false, 1)->default(0);
            $table->tinyInteger('is_item_category', false, 1)->default(0);
            $table->tinyInteger('is_invoice', false, 1)->default(0);
            $table->tinyInteger('is_pre_account', false, 1)->default(0);
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
