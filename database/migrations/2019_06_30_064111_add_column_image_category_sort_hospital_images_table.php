<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnImageCategorySortHospitalImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital_images', function (Blueprint $table) {
            $table->text('category', 10)->nullable();
            $table->integer('sort')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospital_images', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('sort');
        });
    }
}
