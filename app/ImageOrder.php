<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageOrder extends Model
{

    protected $fillable = ['image_group_number', 'image_location_number', 'name', 'order', 'status', 'created_at', 'updated_at'];

}
