<?php

namespace App;

use App\Enums\Status;
use App\Helpers\EnumTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassificationType extends SoftDeleteModel
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'order', 'status', 'is_editable'
    ];

    public function major_classifications()
    {
        return $this->hasMany('App\MajorClassification');
    }
}
