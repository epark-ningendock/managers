<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacilityStaff extends BaseModel
{
  protected $fillable = [
    'name', 'email', 'login_id', 'password',
  ];
}