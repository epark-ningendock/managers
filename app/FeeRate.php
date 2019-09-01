<?php

namespace App;


class FeeRate extends SoftDeleteModel
{
    const FEE_RATE = 0; // 通常手数料
    const PRE_PAYMENT_FEE_RATE = 1; // 事前決済手数料
    
    protected $fillable = [ 'hospital_id', 'type', 'rate', 'from_date', 'to_date' ];
}
