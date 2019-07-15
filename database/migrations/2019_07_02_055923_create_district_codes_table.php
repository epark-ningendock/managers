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
            $table->unsignedInteger('district_code')->default(0000000);
            $table->tinyInteger('prefecture_id')->nullable();
            $table->string('name', 50)->nullable();
            $table->string('kana', 50)->nullable();
            $table->char('status')->default('1')->nullable();
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
