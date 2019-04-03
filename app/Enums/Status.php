<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Status extends Enum implements LocalizedEnum
{
    const Valid = 1;
    const Invalid = 2;
    const Deleted = 99;
}
