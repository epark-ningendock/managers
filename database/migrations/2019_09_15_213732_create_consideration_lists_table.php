<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class ConsiderationListsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consideration_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('epark_member_id');
            $table->integer('hospital_id',10)->nullable();
            $table->foreign('hospital_id')->references('id')->on('hospitals');
            $table->integer('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses');
            $table->integer('flg_display', 1)->default(0);
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
        Schema::dropIfExists('hospital_middle_classifications');
    }
}
