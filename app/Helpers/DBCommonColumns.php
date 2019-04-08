<?php
namespace  App\Helpers;

use Illuminate\Database\Schema\Blueprint;

/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-01
 * Time: 20:51
 */
trait DBCommonColumns {
    /**
     * @param Blueprint $table
     * @return void
     */
    function addCommonColumns(Blueprint $table) {
//        $table->integer('author');
//        $table->integer('changer')->nullable();
//        $table->integer('remover')->nullable();
//        $table->timestamp('deleted_at', 0)->nullable();
        $table->timestamps();
    }
}