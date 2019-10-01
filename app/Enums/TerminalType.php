<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class TerminalType extends Enum implements LocalizedEnum
{
    const HOSPITAL = 1;
    const PC = 2;
    const SMART_PHONE  = 3;
    const PHONE_RESERVATION_API = 4;
    const PHONE_RESERVATION_PPC = 5;
}
