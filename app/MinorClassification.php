<?php

namespace App;

class MinorClassification extends SoftDeleteModel
{
    protected $fillable = [
        'major_classification_id', 'middle_classification_id', 'name', 'is_fregist', 'status', 'order', 'max_length', 'is_icon', 'icon_name'
    ];

    public function major_classification()
    {
        return $this->belongsTo('App\MajorClassification')->withTrashed();
    }

    public function middle_classification()
    {
        return $this->belongsTo('App\MiddleClassification')->withTrashed();
    }

    /**
     * @return string
     */
    public function getUpdatedAtStrAttribute()
    {
        return $this->updated_at->format('Y/m/d').'<br>'.$this->updated_at->format('H:i:s');
    }
}
