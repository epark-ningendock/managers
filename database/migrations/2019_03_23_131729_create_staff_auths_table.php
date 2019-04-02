<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Helpers\DBCommonColumns;

class CreateStaffAuthsTable extends Migration
{
    use DBCommonColumns;
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
            $table->integer('staff_id')->unsigned()->unique();
            $table->foreign('staff_id')->references('id')->on('staffs');
            $this->addCommonColumns($table);
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
