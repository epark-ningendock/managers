<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateHospitalMetaTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id')->unsigned();
            $table->string('hospital_name')->nullable();
            $table->text('area_station')->nullable();
            $table->tinyInteger('credit_card_flg')->default(0);
            $table->tinyInteger('parking_flg')->default(0);
            $table->tinyInteger('pick_up_flg')->default(0);
            $table->tinyInteger('children_flg')->default(0);
            $table->tinyInteger('dedicate_floor_flg')->default(0);

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
        Schema::dropIfExists('hospital_metas');
    }
}
