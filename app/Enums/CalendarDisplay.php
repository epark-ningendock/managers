<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class CalendarDisplay extends Enum implements LocalizedEnum
{
    const Hide = 0;
    const Show = 1;
}
