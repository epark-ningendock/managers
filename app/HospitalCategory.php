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


}
