<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Helpers\DBCommonColumns;

class CreateOldOptionsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hospital_no');
            $table->integer('option_group_cd');
            $table->integer('option_cd');
            $table->integer('option_id');
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
        Schema::dropIfExists('old_options');
    }
}
