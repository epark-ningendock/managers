<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Authority extends Enum implements LocalizedEnum
{
    const Admin = 1;
    const Member = 2;
    const ExternalStaff = 3;
    const ContractStaff = 3;
}
