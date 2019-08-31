<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalExaminationSystem extends Model
{
	protected $fillable = [
		'name', 'company_name', 'postcode', 'prefecture_id', 'address1', 'address2', 'tel', 'fax', 'staff', 'department_id', 'staff_email'
	];
}
