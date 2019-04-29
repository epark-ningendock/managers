<?php
/**
 * Created by PhpStorm.
 * User: thanhtetaung
 * Date: 2019-04-01
 * Time: 17:06
 */

namespace App;

use App\Enums\Status;
use App\Helpers\CustomSoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


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