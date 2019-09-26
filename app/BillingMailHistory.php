<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingMailHistory extends Model
{
    protected $fillable = ['hospital_id', 'to_address1', 'to_address2', 'to_address3', 'cc_name', 'fax', 'mail_type'];
}
