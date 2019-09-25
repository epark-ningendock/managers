<?php

namespace App;

use App\Filters\Filterable;

class MemberLoginInfo extends SoftDeleteModel
{
    use Filterable;

    protected $fillable = [
        'epark_member_id',
        'mail_info_delivery',
        'nick_use',
        'contact',
        'contact_name',
        'status'
    ];

    protected $guarded = [
        'id',
    ];

}
