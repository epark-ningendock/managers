<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('login_id', 100)->unique();
            $table->string('password', 256);
            $table->rememberToken();
            $table->char('authority', 1);
            // 共通項目
            $table->unsignedInteger('author', false, 10)->nullable();
            $table->unsignedInteger('changer', false, 10)->nullable();
            $table->unsignedInteger('remover', false, 10)->nullable();
            $table->softDeletes(); 
            $table->timestamps();
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
