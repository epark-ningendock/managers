<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ReservationStatus extends Enum implements LocalizedEnum
{
    const PENDING = '1';
    const RECEPTION_COMPLETED = '3';
    const COMPLETED = '4';
    const CANCELLED = '5';
}
