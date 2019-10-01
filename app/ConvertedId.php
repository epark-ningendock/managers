<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * コンバート用に利用するクラス
 * This class is only to use converting data.
 *
 * Class ConvertedId
 * @package App
 */
class ConvertedId extends Model
{
    protected $fillable = [
        'table_name',
        'old_id',
        'new_id',
    ];
}
