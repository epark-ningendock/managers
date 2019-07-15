<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateHospitalMinorClassificationsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_minor_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('middle_classification_id')->unsigned();
            $table->foreign('middle_classification_id')->references('id')->on('hospital_middle_classifications');
            $table->string('name', 100);
            $table->char('status', 1)->default('1');
            $table->char('is_fregist', 1)->default('0');
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
        Schema::dropIfExists('hospital_minor_classifications');
    }
}
