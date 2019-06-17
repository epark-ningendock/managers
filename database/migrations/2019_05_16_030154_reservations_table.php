<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class ReservationsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->date('reservation_date');
            $table->string('start_time_hour', 2)->nullable();
            $table->string('start_time_min', 2)->nullable();
            $table->string('end_time_hour', 2)->nullable();
            $table->string('end_time_min', 2)->nullable();
            $table->char('channel', 1)->nullable()->default(0);
            $table->char('reservation_status');
            $table->datetime('completed_date')->nullable();
            $table->datetime('cancel_date')->nullable();
            $table->string('user_message', 255)->nullable();
            $table->string('site_code')->nullable();
            $table->integer('customer_id')->nullable()->unsigned();
            $table->string('epark_member_id')->nullable();
            $table->integer('member_number')->nullable();
            $table->tinyInteger('terminal_type');
            $table->tinyInteger('time_selected')->nullable();
            $table->boolean('is_repeat');
            $table->boolean('is_representative');
            $table->string('timezone_pattern_id');
            $table->string('timezone_id');
            $table->string('order');
            $table->Integer('tax_included_price')->nullable();
            $table->Integer('adjustment_price')->nullable();
            $table->Integer('tax_rate')->nullable();
            $table->date('second_date')->nullable();
            $table->date('third_date')->nullable();
            $table->tinyInteger('is_choose')->nullable();
            $table->string('campaign_code', 50)->nullable();
            $table->integer('tel_timezone')->nullable();
            $table->integer('insurance_assoc_id')->nullable();
            $table->string('insurance_assoc')->nullable();
            $table->char('mail_type', 1);
            $table->string('cancelled_appoint_code')->nullable();
            $table->char('is_billable', 1)->default('0');
            $table->string('claim_month')->nullable();
            $table->string('is_payment')->nullable();
            $table->string('payment_status');
            $table->string('trade_id');
            $table->integer('order_id')->nullable();
            $table->integer('settlement_price')->nullable();
            $table->string('payment_method');
            $table->integer('cashpo_used_price')->nullable();
            $table->string('amount_unsettled')->nullable();
            $table->string('reservation_memo')->nullable();
            $table->string('todays_memo')->nullable();
            $table->string('internal_memo')->nullable();
            $table->integer('acceptance_number')->nullable();
            $table->string('y_uid')->nullable();
            $table->foreign('hospital_id')->references('id')->on('hospitals');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('customer_id')->references('id')->on('customers');
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
        Schema::dropIfExists('reservations');
    }
}
