<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateOptionFutanConditionsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_futan_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kenshin_sys_option_id')->unsigned();
            $table->integer('sex');
            $table->integer('honnin_kbn');
            $table->integer('futan_kingaku');
            $table->integer('yusen_kbn');
            $table->date('riyou_bgn_date');
            $table->date('riyou_end_date');
            $table->char('status', 1)->default('1');
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
        Schema::dropIfExists('option_futan_conditions');
    }
}
