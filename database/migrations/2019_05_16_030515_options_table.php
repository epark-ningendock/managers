<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class OptionsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('hospital_id')->unsigned();
            $table->string('name',40);
            $table->text('confirm')->nullable();
            $table->integer('price')->nullable()->unsigned();
            $table->tinyInteger('tax_class_id')->nullable()->unsigned();
            $table->tinyInteger('order')->unsigined();
            $table->char('status',1)->default('1');
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
        Schema::dropIfExists('options');
    }
}
