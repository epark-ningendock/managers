<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateMajorClassificationsTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('major_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('classification_type_id')->unsigned();
            $table->foreign('classification_type_id')->references('id')->on('classification_types');
            $table->string('name', 100);
            $table->char('status', 1)->default('1');
            $table->tinyInteger('order')->default(0);
            $table->char('is_icon', 1)->default('0');
            $table->string('icon_name', 100)->nullable();
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
        Schema::dropIfExists('major_classifications');
    }
}
