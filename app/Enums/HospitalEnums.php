<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class HospitalEnums extends Enum implements LocalizedEnum
{
    const Public = '1';
    const Private = '0';
    const Delete = 'X';
}
