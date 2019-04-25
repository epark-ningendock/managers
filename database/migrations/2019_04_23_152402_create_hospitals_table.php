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
            $table->string('karada_dog_id', 20)->nullable();
            $table->string('code', 20)->nullable();
	        $table->string('old_karada_dog_id', 20);
	        $table->string('name', 200)->nullable();
	        $table->string('kana', 100)->nullable();
	        $table->char('zip_code', 8);
	        $table->tinyInteger('pref');
	        $table->integer('district_code_id');
	        $table->string('address1', 256);
	        $table->string('address2', 256);
	        $table->decimal('longitude', 11, 7);
	        $table->decimal('latitude', 11, 7);
	        $table->integer('direction');
	        $table->text('streetview_url');
	        $table->string('tel', 30);
	        $table->string('paycall', 30);
	        $table->string('fax', 30);
	        $table->string('email', 256);
	        $table->string('url', 256);
	        $table->string('consultation_note', 256);
	        $table->text('memo');
	        $table->string('business_hours', 256);
	        $table->integer('rail1');
	        $table->integer('station1');
	        $table->string('access1', 50);
	        $table->integer('rail2');
	        $table->integer('station2');
	        $table->string('access2', 50);
	        $table->integer('rail3');
	        $table->integer('station3');
	        $table->string('access3', 50);
	        $table->integer('rail4');
	        $table->integer('station4');
	        $table->string('access4', 50);
	        $table->integer('rail5');
	        $table->integer('station5');
	        $table->string('access5', 50);
	        $table->text('memo1');
	        $table->text('memo2');
	        $table->text('memo3');
	        $table->string('principal', 50);
	        $table->text('principal_history');
	        $table->integer('pv_count')->default(0)->nullable();
	        $table->integer('pvad')->default(0)->nullable();
	        $table->integer('is_pickup');
	        $table->integer('hospital_staff_id')->nullable();
	        $table->string('login_id', 32);
	        $table->string('login_psw', 256);
	        $table->char('login_status')->default(0);
	        $table->char('status')->default(0)->nullable();
	        $table->char('certified_facility')->default(0);
	        $table->text('free_area');
	        $table->text('search_word');
	        $table->char('plan_code', 2);
	        $table->char('hplink_contract_type', 1)->default(0);
	        $table->integer('hplink_count');
	        $table->integer('hplink_price');
	        $table->string('created_id', 50);
	        $table->string('updated_id', 50);
	        $table->char('pre_account_flg')->nullable();
	        $table->integer('pre_account_discount_rate')->nullable();
	        $table->decimal('pre_account_commission_rate',5,2);
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
