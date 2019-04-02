<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Authority extends Enum
{
    const Admin = 1;
    const Member = 2;
    const ExternalStaff = 3;
}
