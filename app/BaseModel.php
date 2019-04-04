<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-01
 * Time: 17:06
 */

namespace App;

use Illuminate\Database\Eloquent\Model;


class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();
        
//        static::creating(function($model) {
//            if (Auth::check()) {
//                $model->author = Auth::user()->id;
//            }
//        });
//
//        static::updating(function($model) {
//            if (Auth::check()) {
//                $model->changer = Auth::user()->id;
//            }
//        });
//
//        static::deleting(function($model) {
//            if (Auth::check()) {
//                $model->remover = Auth::user()->id;
//            }
//        });
    }

}