<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('kana', 100);
            $table->char('postcode', 8)->nullable();
            $table->integer('district_code_id')->nullable();
            $table->unsignedInteger('course_meta_information_id')->nullable();
            $table->string('address1', 256)->nullable();
            $table->string('address2', 256)->nullable();
            $table->decimal('longitude', 11, 7)->nullable();
            $table->decimal('latitude', 11, 7)->nullable();
            $table->integer('direction')->nullable();
            $table->text('streetview_url')->nullable();
            $table->string('tel', 30)->nullable();
            $table->string('paycall', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('url', 256)->nullable();
            $table->string('consultation_note', 256)->nullable();
            $table->text('memo')->nullable();
            $table->unsignedInteger('medical_examination_system_id')->nullable();
            $table->unsignedInteger('rail1')->nullable();
            $table->unsignedInteger('station1')->nullable();
            $table->string('access1', 50)->nullable();
            $table->unsignedInteger('rail2')->nullable();
            $table->unsignedInteger('station2')->nullable();
            $table->string('access2', 50)->nullable();
            $table->unsignedInteger('rail3')->nullable();
            $table->unsignedInteger('station3')->nullable();
            $table->string('access3', 50)->nullable();
            $table->unsignedInteger('rail4')->nullable();
            $table->unsignedInteger('station4')->nullable();
            $table->string('access4', 50)->nullable();
            $table->unsignedInteger('rail5')->nullable();
            $table->unsignedInteger('station5')->nullable();
            $table->string('access5', 50)->nullable();
            $table->text('memo1')->nullable();
            $table->text('memo2')->nullable();
            $table->text('memo3')->nullable();
            $table->string('principal', 50)->nullable();
            $table->text('principal_history')->nullable();
            $table->integer('pv_count')->default(0);
            $table->integer('pvad')->default(0);
            $table->integer('is_pickup')->nullable();
            $table->unsignedInteger('hospital_staff_id');
            $table->char('status')->default(0);
            $table->text('free_area')->nullable();
            $table->text('search_word')->nullable();
            $table->char('plan_code', 2)->nullable();
            $table->char('hplink_contract_type', 1)->default(0)->nullable();
            $table->integer('hplink_count')->nullable();
            $table->integer('hplink_price')->nullable();
            $table->char('is_pre_account')->nullable();
            $table->integer('pre_account_discount_rate')->nullable();
            $table->decimal('pre_account_commission_rate', 5, 2)->nullable();
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
        Schema::dropIfExists('hospitals');
    }
}
