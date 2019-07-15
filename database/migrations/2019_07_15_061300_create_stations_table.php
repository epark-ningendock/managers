<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('stations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('es_code')->unique()->default(0);
            $table->unsignedTinyInteger('prefecture_id')->default(0);
            $table->string('name', 100)->nullable();
            $table->string('kana', 100)->nullable();
            $table->string('longitude', 100)->nullable();
            $table->string('latitude', 100)->nullable();
            $table->char('status')->nullable();
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
        Schema::dropIfExists('stations');
    }
}
