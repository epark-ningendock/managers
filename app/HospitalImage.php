<?php

namespace App;

class HospitalImage extends SoftDeleteModel
{
    protected $guarded = ['id'];

    public function hospital_category()
    {
        return $this->hasOne('App\HospitalCategory');
    }

    public function image_order()
    {
        return $this->hasOne('App\ImageOrder');
    }

    public function scopeFindByid($query, $id)
    {
        $query->where('id',$id)->where('image_order',$image_order);

        return $query;
    }


}
