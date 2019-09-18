<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalPlan extends Model
{
	protected $fillable = ['hospital_id', 'contract _plan_id', 'from', 'to'];

	protected $dates = ['from', 'to'];
}
