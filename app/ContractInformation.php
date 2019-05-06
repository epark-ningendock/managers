<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractInformation extends Model
{
    protected $fillable = ['contractor_name_kana', 'contractor_name', 'application_date', 'billing_start_date', 'cancellation_date', 'representative_name_kana', 'representative_name', 'postcode', 'address', 'tel', 'fax', 'email', 'karada_dog_id', 'code', 'old_karada_dog_id', 'hospital_staff_id'];
}
