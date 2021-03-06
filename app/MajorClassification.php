<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class MajorClassification extends SoftDeleteModel
{
    use SoftDeletes;

    protected $fillable = [
        'classification_type_id', 'name', 'status', 'order', 'is_icon', 'icon_name', 'created_at', 'updated_at'
    ];

    public function classification_type()
    {
        return $this->belongsTo('App\ClassificationType');
    }

    public function middle_classifications()
    {
        return $this->hasMany('App\MiddleClassification')->orderBy('order');
    }

    public function minor_classifications()
    {
        return $this->hasMany('App\MinorClassification')->orderBy('order');
    }

    /**
     * @return string
     */
    public function getUpdatedAtStrAttribute()
    {
        return $this->updated_at->format('Y/m/d') . '<br>' . $this->updated_at->format('H:i:s');
    }
}
