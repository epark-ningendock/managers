<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class HospitalEnums extends Enum implements LocalizedEnum
{
    const PUBLIC = '1';
    const PRIVATE = '0';
    const DELETE = 'X';
}
