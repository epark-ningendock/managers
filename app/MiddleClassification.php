<?php

namespace App;

class MiddleClassification extends SoftDeleteModel
{
    protected $fillable = [
        'id', 'major_classification_id', 'name', 'status', 'order', 'is_icon', 'icon_name'
    ];

    public function major_classification()
    {
        return $this->belongsTo('App\MajorClassification')->withTrashed();
    }

    public function minor_classifications()
    {
        return $this->hasMany('App\MinorClassification')->orderBy('order');
    }

    public function minors_with_fregist_order()
    {
        return $this->hasMany('App\MinorClassification')->orderBy('is_fregist', 'DESC');
    }

    /**
     * @return string
     */
    public function getUpdatedAtStrAttribute()
    {
        return $this->updated_at->format('Y/m/d') . '<br>' . $this->updated_at->format('H:i:s');
    }
}
