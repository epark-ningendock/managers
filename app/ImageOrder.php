<?php

namespace App;


class ImageOrder extends SoftDeleteModel
{
    protected $fillable = [ 'image_group_number', 'image_location_number', 'name', 'order', 'status' ];
}
