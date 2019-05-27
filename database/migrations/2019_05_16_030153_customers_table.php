<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CustomersTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_customer_id')->unsigned()->nullable();
            $table->integer('member_number')->unsigned()->nullable();
            $table->string('registration_card_number',32)->nullable();
            $table->string('name',65)->nullable();
            $table->string('name_kana',65)->nullable();
            $table->string('tel',13)->nullable();
            $table->string('email',255)->nullable();
            $table->string('postcode',8)->nullable();
            $table->integer('prefecture_id')->nullable();
            $table->string('address',200)->nullable();
            $table->char('sex',1)->nullable();
            $table->string('birthday',8)->nullable();
            $table->string('memo',255)->nullable();
            $table->integer('claim_count')->unsigned()->default(0);
            $table->integer('recall_count')->unsigned()->default(0);
            $table->unique('email');
            $table->unique('member_number');
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
        Schema::dropIfExists('customers');
    }
}
