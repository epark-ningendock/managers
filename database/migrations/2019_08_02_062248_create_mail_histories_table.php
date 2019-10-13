<?php

use App\Helpers\DBCommonColumns;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailHistoriesTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 256);
            $table->datetime('sent_datetime');
            $table->string('sender_name', 256);
            $table->string('sender_address', 256);
            $table->string('title', 256);
            $table->text('contents');
            $table->char('status', 1)->default('1');
            $table->integer('customer_id')->unsigned();
//            $table->foreign('customer_id')->references('id')->on('customers'); // import のためにコメントアウト
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
        Schema::dropIfExists('mail_histories');
    }
}
