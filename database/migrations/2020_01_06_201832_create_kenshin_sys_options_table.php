<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateKenshinSysOptionsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kenshin_sys_options', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('kenshin_sys_course_id')->unsigned();
            $table->bigInteger('kenshin_sys_option_no');
            $table->string('kenshin_sys_option_name', 100);
            $table->integer('kenshin_sys_option_age_kisan_kbn');
            $table->date('kenshin_sys_option_age_kisan_date')->nullable();
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
        Schema::dropIfExists('kenshin_sys_options');
    }
}
