<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

<<<<<<< HEAD:database/migrations/2019_10_13_230817_create_option_target_ages_table.php
class CreateOptionTargetAgesTable extends Migration
=======
class CreateOptionPlansTable extends Migration
>>>>>>> cc7d87dcddc5d35678d1a05d626403781e11bebf:database/migrations/2019_11_15_142138_create_option_plans_table.php
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<< HEAD:database/migrations/2019_10_13_230817_create_option_target_ages_table.php
        Schema::create('option_target_ages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_futan_condition_id')->unsigned();
            $table->integer('target_age');
=======
        Schema::create('option_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('option_plan_name');
            $table->integer('option_plan_price')->default(0);

>>>>>>> cc7d87dcddc5d35678d1a05d626403781e11bebf:database/migrations/2019_11_15_142138_create_option_plans_table.php
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
<<<<<<< HEAD:database/migrations/2019_10_13_230817_create_option_target_ages_table.php
        Schema::dropIfExists('option_target_ages');
=======
        Schema::dropIfExists('option_plans');
>>>>>>> cc7d87dcddc5d35678d1a05d626403781e11bebf:database/migrations/2019_11_15_142138_create_option_plans_table.php
    }
}
