<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateRailwayCompaniesTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('railway_companies', function (Blueprint $table) {
            $table->integer('es_code')->default(0)->comment('駅すぱあとコード');
            $table->string('name', 100)->nullable()->comment('路線名');
            $table->char('status', 1)->default('1')->comment('状態');
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
        Schema::dropIfExists('railway_companies');
    }
}
