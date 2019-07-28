<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Helpers\DBCommonColumns;

class CreateStaffsTable extends Migration
{
    use DBCommonColumns;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->string('login_id', 100)->unique()->nullable();
            $table->string('password', 256);
            $table->tinyInteger('authority', false, 1);
            $table->char('status', 2)->default('1');
            $table->string('email', 256)->unique()->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->dateTime('first_login_at')->nullable();
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
        Schema::dropIfExists('staffs');
    }
}
