<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class HplinkContractType extends Enum implements LocalizedEnum
{
    const NONE = 0;                  // 無し
    const PAY_PER_USE = 1;           // 従量課金
    const MONTHLY_SUBSCRIPTION = 2;  // 月額固定
}
