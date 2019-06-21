<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnCustomerNameCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('family_name', 'family_name');
            $table->renameColumn('first_name', 'first_name');
            $table->renameColumn('name_kana_seri', 'first_name_kana');
            $table->renameColumn('name_kana_mei', 'family_name_kana');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('family_name', 'family_name');
            $table->renameColumn('first_name', 'first_name');
            $table->renameColumn('first_name_kana', 'name_kana_seri');
            $table->renameColumn('family_name_kana', 'name_kana_mei');
        });
    }
}
