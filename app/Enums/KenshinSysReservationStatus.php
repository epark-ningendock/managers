<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class KenshinSysReservationStatus extends Enum implements LocalizedEnum
{
    const PENDING = '1';
    const RECEPTION_COMPLETED = '2';
    const CANCELLED = '3';
    const COMPLETED = '4';
}
