<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateMemberLoginInfosTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_login_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('epark_member_id');
            $table->char('mail_info_delivery',1)->default('1');
            $table->char('nick_use', 1)->default('1');
            $table->tinyInteger('contact')->default(1);
            $table->string('contact_name', 32)->nullable();
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
        Schema::dropIfExists('member_login_infos');
    }
}
