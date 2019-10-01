<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BillingStatus extends Enum
{
    const UNCONFIRMED = 1;
    const CHECKING = 2;
    const CONFIRMED = 3;
    const CONFIRM = 4;
}
