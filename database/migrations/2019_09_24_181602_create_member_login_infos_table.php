<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberLoginInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_login_info', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('epark_member_id');
            $table->char('mail_info_delivery', 1)->default(1);
            $table->char('nick_use', 1)->default(1);
            $table->unsignedTinyInteger('contact')->default(1);
            $table->string('contact_name', 32)->nullable();
            $table->char('status', 1)->default(1);
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
        Schema::dropIfExists('member_login_info');
    }
}
