<?php

namespace App;


class NameIntegration extends SoftDeleteModel
{
    protected $fillable = [ 'customer_id', 'integrated_customer_id' ];
}
