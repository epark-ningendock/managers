<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Authority extends Enum implements LocalizedEnum
{
    const ADMIN = 1;
    const MEMBER = 2;
    const EXTERNAL_STAFF = 3;
    const CONTRACT_STAFF = 4;
}
