<?php

namespace App;

use App\Helpers\EnumTrait;
use App\Enums\Status;

class MiddleClassification extends SoftDeleteModel
{
    use EnumTrait;

    protected $fillable = [
        'major_classification_id', 'name', 'status', 'order', 'is_icon', 'icon_name'
    ];

    protected $enums = [
        'status' => Status::class
    ];

    public function major_classification() {
        return $this->belongsTo('App\MajorClassification')->withTrashed();
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
