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
use App\Helpers\EnumTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


class SoftDeleteModel extends BaseModel
{
    use EnumTrait;
    use SoftDeletes;


    protected $enums = [
        'status' => Status::class
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function bootSoftDeletes()
    {
        static::addGlobalScope(new CustomSoftDeletingScope());
    }

    protected function runSoftDelete()
    {

        $query = $this->newModelQuery()->where($this->getKeyName(), $this->getKey());

        $time = $this->freshTimestamp();

        $columns = [
            $this->getDeletedAtColumn() => $this->fromDateTime($time),
            'status' => Status::Deleted
        ];

        $this->{$this->getDeletedAtColumn()} = $time;
        $this->status = Status::Deleted;

        if ($this->timestamps && ! is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);

    }

}