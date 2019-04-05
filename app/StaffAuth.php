<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffAuth extends BaseModel
{

    protected $fillable = [
        'is_hospital', 'is_staff', 'is_item_category', 'is_invoice', 'is_pre_account'
    ];

    /**
     * このスタッフを所有するstaff_authを取得
     */
    public function staff()
    {
        return $this->belongsTo('App\Staff');
    }
}
