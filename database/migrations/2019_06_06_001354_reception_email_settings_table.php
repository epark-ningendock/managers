<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class ReceptionEmailSettingsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reception_email_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id')->unsigned()->unique();
            $table->foreign('hospital_id')->references('id')->on('hospitals');
            $table->tinyInteger('in_hospital_email_reception_flg')->default(0);
            $table->tinyInteger('in_hospital_confirmation_email_reception_flg') ->default(0);
            $table->tinyInteger('in_hospital_change_email_reception_flg')->nullable()->default(0);
            $table->tinyInteger('in_hospital_cancellation_email_reception_flg')->nullable()->default(0);
            $table->tinyInteger('email_reception_flg')->default(0);
            $table->tinyInteger('in_hospital_reception_email_flg')->nullable()->default(0);
            $table->tinyInteger('web_reception_email_flg')->nullable()->default(0);
            $table->string('reception_email1')->nullable();
            $table->string('reception_email2')->nullable();
            $table->string('reception_email3')->nullable();
            $table->string('reception_email4')->nullable();
            $table->string('reception_email5')->nullable();
            $table->tinyInteger('epark_in_hospital_reception_mail_flg')->nullable()->default(0);
            $table->tinyInteger('epark_web_reception_email_flg')->nullable()->default(0);
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
        Schema::dropIfExists('reception_email_settings');
    }
}
