<?php

namespace App;

use App\Helpers\EnumTrait;
use App\Enums\Status;

class MajorClassification extends SoftDeleteModel
{
    use EnumTrait;

    protected $fillable = [
        'classification_type_id', 'name', 'status', 'order', 'is_icon', 'icon_name'
    ];

    protected $enums = [
        'status' => Status::class
    ];

    public function classification_type() {
        return $this->belongsTo('App\ClassificationType');
    }

    public function middle_classifications() {
        return $this->hasMany('App\MiddleClassification');
    }

    public function minor_classifications() {
        return $this->hasMany('App\MinorClassification');
    }

    /**
     * @return string
     */
    public function getUpdatedAtStrAttribute()
    {
        return $this->updated_at->format('Y/m/d').'<br>'.$this->updated_at->format('H:i:s');
    }
}
