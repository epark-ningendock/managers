<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalExaminationSystemsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_examination_systems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('company_name');
            $table->string('postcode')->nullable();
            $table->tinyInteger('prefecture_id')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('tel')->nullable();
            $table->string('fax')->nullable();
            $table->string('staff')->nullable();
            $table->string('department_id')->nullable();
            $table->string('staff_email')->nullable();
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
        Schema::dropIfExists('medical_examination_systems');
    }
}
