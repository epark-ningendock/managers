<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class FacilityStaff extends Authenticatable
{
  protected $fillable = [
    'name', 'email', 'login_id', 'password',
  ];

  protected $table = 'facility_staffs';
}
