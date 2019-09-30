<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberLoginInfo extends Model
{
    protected $table = 'member_login_info';

    protected $fillable = [
        'epark_member_id',
        'mail_info_delivery',
        'nick_use',
        'contact',
        'contact_name',
        'status',
    ];
}
