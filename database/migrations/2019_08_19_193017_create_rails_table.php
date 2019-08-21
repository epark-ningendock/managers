<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRailsTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rails', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('es_code')->nullable()->unique();
            $table->unsignedBigInteger('railway_company_id')->default(0)->index();
            $table->string('name', 50)->nullable();
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
        Schema::dropIfExists('rails');
    }
}
