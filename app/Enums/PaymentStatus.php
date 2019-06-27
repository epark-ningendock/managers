<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class PaymentStatus extends Enum implements LocalizedEnum
{
    const TEMPORARY_SALE = '1';
    const ACTUAL_SALE = '2';
    const CANCELLATION = '3';
    const ERROR = '9';
}
