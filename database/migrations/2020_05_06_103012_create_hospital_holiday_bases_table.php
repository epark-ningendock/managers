<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateHospitalHolidayBasesTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_holiday_bases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id')->unsigned()->nullable();
            $table->tinyInteger('mon_hol_flg')->unsigned();
            $table->tinyInteger('tue_hol_flg')->unsigned();
            $table->tinyInteger('wed_hol_flg')->unsigned();
            $table->tinyInteger('thu_hol_flg')->unsigned();
            $table->tinyInteger('fri_hol_flg')->unsigned();
            $table->tinyInteger('sat_hol_flg')->unsigned();
            $table->tinyInteger('sun_hol_flg')->unsigned();
            $table->tinyInteger('hol_hol_flg')->unsigned();
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
        Schema::dropIfExists('calendar_base_wakus');
    }
}
