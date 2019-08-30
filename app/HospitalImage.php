<?php

namespace App;

class HospitalImage extends SoftDeleteModel
{
    protected $guarded = ['id'];
    const SP_IMAGE_WIDTH = 750;// スマホ用リサイズ画像サイズWIDTH

    public function hospital_category()
    {
        return $this->hasOne('App\HospitalCategory');
    }

    public function image_order()
    {
        return $this->hasOne('App\ImageOrder');
    }
    public function scopeByImageName($query, $image_name)
    {
        $query->where('name',$image_name);

        return $query;
    }

}
