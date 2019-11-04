<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WakuInfo extends Enum implements LocalizedEnum
{
    const OUT_OF_TERM = 0;
    const WAKU_EXIST = 1;
    const WAKU_ZERO = 2;
}
