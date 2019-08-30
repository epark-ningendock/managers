<?php

namespace App;
use Reshadman\OptimisticLocking\OptimisticLocking;

class HospitalImage extends SoftDeleteModel
{
    protected $guarded = ['id'];
    use OptimisticLocking;
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
