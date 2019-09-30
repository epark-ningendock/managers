<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalCategory extends SoftDeleteModel
{
    use SoftDeletes;
    //image_group_number
    const TAB_CATEGORY_STAFF = 1;//タブ スタッフ
    const TAB_CATEGORY_FACILITY = 2;//タブ 設備
    const TAB_CATEGORY_INTERNAL = 3;//タブ 院内
    const TAB_CATEGORY_EXTERNAL = 4;//外観
    const TAB_CATEGORY_ANOTHER = 5;//その他
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

    public function image_order()
    {
        return $this->belongsTo('App\ImageOrder');
    }

    public function minor_classification()
    {
        return $this->belongsTo('App\HospitalMinorClassification', 'minor_classification_id')->withTrashed();
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
