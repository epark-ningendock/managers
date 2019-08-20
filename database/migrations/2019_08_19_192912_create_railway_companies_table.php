<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id');
            $table->unsignedBigInteger('es_code')->nullable()->unique();
            $table->string('name')->nullable();
            $table->char('status', 1);
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
