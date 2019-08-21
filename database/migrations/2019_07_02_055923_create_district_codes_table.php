<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->char('district_code', 7)->default('0000000');
            $table->unsignedInteger('prefecture_id')->nullable();
            $table->string('name')->nullable();
            $table->string('kana')->nullable();
            $table->char('status', 1)->nullable()->default(1);
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
