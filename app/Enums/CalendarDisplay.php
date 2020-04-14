<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class CalendarDisplay extends Enum implements LocalizedEnum
{
    const HIDE = 1;
    const SHOW = 0;
}
