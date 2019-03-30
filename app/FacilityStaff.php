<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityStaff extends Model
{
  protected $fillable = [
    'name', 'email', 'login_id', 'password', 'authority',
  ];
}
