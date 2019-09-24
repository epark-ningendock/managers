<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BillingStatus extends Enum
{
    const Unconfirmed = 1;
    const Checking = 2;
    const Confirmed = 3;
    const Confirm = 4;
}
