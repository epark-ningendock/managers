<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class AppKbn extends Enum implements LocalizedEnum
{
    const PRODUCTION = '1';
    const OTHER = '2';
}
