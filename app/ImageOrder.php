<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageOrder extends Model
{

    protected $fillable = ['image_group_number', 'image_location_number', 'name', 'order', 'status', 'created_at', 'updated_at'];

    public function hospital_category()
    {
        return $this->hasMany('App\HospitalCategory', 'id', 'image_order');
    }
}
