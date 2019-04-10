<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HospitalStaff extends Model
{

	protected $fillable = [
		'name', 'email', 'login_id', 'password',
	];
}
