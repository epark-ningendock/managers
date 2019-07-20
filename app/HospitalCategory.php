<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalCategory extends SoftDeleteModel
{
    use SoftDeletes;

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
