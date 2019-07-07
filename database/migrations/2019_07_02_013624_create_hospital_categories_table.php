<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateHospitalCategoriesTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id')->unsigned()->nullable();
            $table->foreign('hospital_id')->references('id')->on('hospitals');
            $table->integer('hospital_image_id')->unsigned()->nullable();
            $table->integer('image_order')->unsigned()->nullable();
            $table->text('title')->nullable();
            $table->text('caption')->nullable();
            $table->text('name')->nullable();
            $table->text('memo')->nullable();
            $table->text('career')->nullable();
            $table->text('interview')->nullable();
            $table->tinyInteger('is_display')->default(0);
            $table->integer('order')->default(0);
            $table->integer('order2')->default(0);
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
        Schema::dropIfExists('hospital_categories');
    }
}
