<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class StaffStatus extends Enum implements LocalizedEnum
{
    const VALID = 1;
    const INVALID = 2;
    const DELETED = 99;
}
