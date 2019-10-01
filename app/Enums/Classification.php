<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Classification extends Enum implements LocalizedEnum
{
    const MAJOR = 'MAJOR';
    const MIDDLE = 'MIDDLE';
    const MINOR = 'MINOR';
}
