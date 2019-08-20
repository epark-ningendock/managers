<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedBigInteger('es_code')->nullable()->unique();
            $table->unsignedTinyInteger('prefecture_id')->default(0);
            $table->string('name', 100)->nullable();
            $table->string('kana', 100)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->string('latitude', 20)->nullable();
            $table->char('status', 1)->nullable();
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
