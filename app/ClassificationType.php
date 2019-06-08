<?php

namespace App;

use App\Enums\Status;
use App\Helpers\EnumTrait;

class ClassificationType extends SoftDeleteModel
{
    protected $fillable = [
        'name', 'order', 'status', 'is_editable'
    ];

    public function major_classifications()
    {
        return $this->hasMany('App\MajorClassification');
    }
}
