<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\DBCommonColumns;

class CreateCourseMetaInformationTable extends Migration
{
    use DBCommonColumns;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_meta_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned()->comment('検査コースID');
            $table->text('inspection_minor_classification')->nullable()->comment('検査小分類名称');
            $table->text('hospital_classification')->nullable()->comment('医療機関小分類名称');
            $table->text('area_station')->nullable()->comment('都道府県,自治体名,駅名');
            $table->text('freewords')->nullable()->comment('検索フリーワード');
            $table->text('rails')->nullable()->comment('路線コード一覧');

            // unique
            // foreign key
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
        Schema::dropIfExists('course_meta_informations');
    }
}
