<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateCoursesTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id')->unsigned()->nullable();
            $table->foreign('hospital_id')->references('id')->on('hospitals');
            $table->integer('calendar_id')->unsigned()->nullable();
//            $table->foreign('calendar_id')->references('id')->on('calendars');
            $table->string('code', 45)->nullable();
            $table->string('name', 64);
            $table->char('web_reception', 1)->nullable();
            $table->integer('is_category')->unsigned()->default(0);
            $table->text('course_sales_copy')->nullable();
            $table->text('course_point')->nullable();
            $table->text('course_notice')->nullable();
            $table->text('course_cancel')->nullable();
            $table->tinyInteger('is_price')->nullable();
            $table->integer('price')->nullable();
            $table->tinyInteger('is_price_memo')->nullable();
            $table->string('price_memo', 50)->nullable();
            $table->integer('regular_price')->nullable();
            $table->integer('discounted_p[rice')->nullable();
            $table->integer('tax_class')->unsigned()->default(0);
            $table->tinyInteger('display_setting')->nullable();
            $table->integer('pv')->unsigned()->default(0);
            $table->integer('pvad')->unsigned()->default(0);
            $table->tinyInteger('order')->unsigned();
            $table->integer('cancellation_deadline')->default(0);
            $table->integer('reception_start_date')->nullable();
            $table->integer('reception_end_date')->nullable();
            $table->integer('pre_account_price')->nullable();
            $table->tinyInteger('is_local_payment')->nullable();
            $table->tinyInteger('is_pre_account')->nullable();
            $table->tinyInteger('auto_calc_application')->default(1);
            $table->integer('reception_acceptance_date')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
