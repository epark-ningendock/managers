<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreatePvRecordsTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pv_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hospital_id')->comment('医療機関ID');
            $table->string('date_code')->comment('日付コード');
            $table->integer('pv')->comment('PV数');
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
        Schema::dropIfExists('pv_records');
    }
}
