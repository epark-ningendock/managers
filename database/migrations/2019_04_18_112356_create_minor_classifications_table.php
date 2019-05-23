<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateMinorClassificationsTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minor_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('major_classification_id')->unsigned();
            $table->foreign('major_classification_id')->references('id')->on('major_classifications');
            $table->integer('middle_classification_id')->unsigned();
            $table->foreign('middle_classification_id')->references('id')->on('middle_classifications');
            $table->string('name', 100);
            $table->char('is_fregist', 1)->default('0');
            $table->smallInteger('order')->default(0);
            $table->smallInteger('max_length')->nullable();
            $table->char('is_icon', 1)->default('0');
            $table->string('icon_name', 100)->nullable();
            $table->char('status', 1)->default('1');
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
        Schema::dropIfExists('minor_classifications');
    }
}
