<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class MiddleClassification extends SoftDeleteModel
{
    use SoftDeletes;

    protected $fillable = [
        'major_classification_id', 'name', 'status', 'order', 'is_icon', 'icon_name', 'created_at', 'updated_at'
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
        return $this->hasMany('App\MinorClassification')->orderBy('order')->orderBy('is_fregist', 'DESC');
    }

    /**
     * @return string
     */
    public function getUpdatedAtStrAttribute()
    {
        return $this->updated_at->format('Y/m/d') . '<br>' . $this->updated_at->format('H:i:s');
    }
}
