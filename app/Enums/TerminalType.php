<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class TerminalType extends Enum implements LocalizedEnum
{
    const Hospital = 1;
    const PC = 2;
    const SmartPhone  = 3;
    const PhoneReservationAPI = 4;
    const PhoneReservationPPC = 5;
}
