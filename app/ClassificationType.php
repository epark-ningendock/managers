<?php

namespace App;
use App\Enums\Status;
use App\Helpers\EnumTrait;


class ClassificationType extends SoftDeleteModel
{
    use EnumTrait;

    protected $fillable = [
        'name', 'order', 'status', 'is_editable'
    ];

    protected $enums = [
        'status' => Status::class
    ];

    public function major_classifications() {
        return $this->hasMany('App\MajorClassification');
    }
}
