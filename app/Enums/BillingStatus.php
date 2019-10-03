<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class BillingStatus extends Enum implements LocalizedEnum
{
    const UNCONFIRMED = 1;
    const CONFIRMING = 2;
    const VERIFIED = 3;
    const FIXED = 4;
    // @todo 適切な値に設定すべし
    const CHECKING = 5;
    const CONFIRMED = 6;
    const CONFIRM = 7;
    // @todo end
}
