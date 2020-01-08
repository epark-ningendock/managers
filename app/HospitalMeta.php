<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalMeta extends SoftDeleteModel
{
    use SoftDeletes;
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $guarded = ['id'];
    protected $fillable = [
        'hospital_id',
        'hospital_name',
        'area_station',
        'credit_card_flg',
        'parking_flg',
        'pick_up_flg',
        'children_flg',
        'dedicate_floor_flg',
        'created_at',
        'updated_at'
    ];

    public function hospitals()
    {
        return $this->belongsTo('App\Hospital');
    }

    public function courseMetas()
    {
        return $this->hasMany('App\CourseMeta', 'hospital_id', 'hospital_id');
    }
}
