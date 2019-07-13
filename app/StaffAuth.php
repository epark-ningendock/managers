<?php

namespace App;

use App\Enums\Permission;
use App\Helpers\EnumTrait;
use Illuminate\Database\Eloquent\Model;

class StaffAuth extends BaseModel
{
    protected $fillable = [
        'is_hospital',
        'is_staff',
        'is_cource_classification',
        'is_invoice',
        'is_pre_account',
        'is_contract'
    ];

    /**
     * このスタッフを所有するstaff_authを取得
     */
    public function staff()
    {
        return $this->belongsTo('App\Staff');
    }
}
