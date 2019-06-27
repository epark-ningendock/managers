<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ReservationStatus extends Enum implements LocalizedEnum
{
    const Pending = '1';
    const ReceptionCompleted = '2';
    const Completed = '3';
    const Cancelled = '4';
}
