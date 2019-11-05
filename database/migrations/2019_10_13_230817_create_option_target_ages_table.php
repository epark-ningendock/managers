<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateOptionTargetAgesTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_target_ages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_futan_condition_id')->unsigned();
            $table->foreign('option_futan_condition_id')->references('id')->on('option_futan_conditions');
            $table->integer('target_age');
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
        Schema::dropIfExists('option_target_ages');
    }
}
