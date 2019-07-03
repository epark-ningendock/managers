<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictCodesTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('major_classification_id');
            $table->string('name', 100);
            $table->tinyInteger('order')->default(0);
            $table->char('is_icon')->default(0);
            $table->string('icon_name', 100);
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
        Schema::dropIfExists('district_codes');
    }
}
