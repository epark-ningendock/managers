<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Rate extends Enum implements LocalizedEnum
{
  const FEE_RATE = 0; // 通常手数料
  const PRE_PAYMENT_FEE_RATE = 1; // 事前決済手数料
}
