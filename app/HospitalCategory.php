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

    public function scopeByImageOrder($query, $hospital_id, $image_order, $order)
    {
        $query->where('hospital_id',$hospital_id)->where('image_order',$image_order)->where('order',$order);

        return $query;
    }

    public function scopeByImageOrderAndFileLocationNo($query, $hospital_id, $image_order, $order, $file_location_no)
    {
        $query->where('hospital_id',$hospital_id)->where('image_order',$image_order)->where('order',$order)->where('file_location_no',$file_location_no);

        return $query;
    }
}
