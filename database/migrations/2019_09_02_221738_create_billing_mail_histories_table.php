<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingMailHistoriesTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_mail_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id');
            $table->string('to_address1')->nullable();
            $table->string('to_address2')->nullable();
            $table->string('to_address3')->nullable();
            $table->string('cc_name');
            $table->string('fax')->nullable();
            $table->tinyInteger('mail_type');
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
        Schema::dropIfExists('billing_mail_histories');
    }
}
