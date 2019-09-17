<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class BillingStatus extends Enum implements LocalizedEnum
{
    const Unconfirmed = 1;
    const Checking = 2;
    const Confirmed = 3;
    const Confirm = 4;
}
