<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalCategory extends SoftDeleteModel
{
    use SoftDeletes;

    const HOSPITAL = 0;//医療機関
    const COURSE = 1;//コース画像
    const FACILITY = 2;//施設画像

    const HIDE = 0;//非表示
    const SHOW = 1;//表示

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function hospital()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function hospital_image()
    {
        return $this->belongsTo('App\HospitalImage');
    }

    public function interview_details()
    {
        return $this->hasMany('App\InterviewDetail');
    }

    public function scopeByImageOrder($query, $hospital_id, $image_order, $order2)
    {
        $query->where('hospital_id',$hospital_id)->where('image_order',$image_order)->where('order2',$order2);

        return $query;
    }


}