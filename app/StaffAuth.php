<?php

namespace App;

use App\Enums\Permission;
use App\Helpers\EnumTrait;
use Illuminate\Database\Eloquent\Model;

class StaffAuth extends BaseModel
{

    use EnumTrait;

    protected $fillable = [
        'is_hospital', 'is_staff', 'is_item_category', 'is_invoice', 'is_pre_account'
    ];

    protected $enums = [
        'is_hospital' => Permission::class,
        'is_staff' => Permission::class,
        'is_item_category' => Permission::class,
        'is_invoice' => Permission::class,
        'is_pre_account' => Permission::class,
    ];

    /**
     * このスタッフを所有するstaff_authを取得
     */
    public function staff()
    {
        return $this->belongsTo('App\Staff');
    }
}
