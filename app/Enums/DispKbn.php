<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class DispKbn extends Enum implements LocalizedEnum
{
    const FACILITY = '0';
    const COURSE = '1';
}
