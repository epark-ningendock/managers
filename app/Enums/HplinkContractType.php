<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class HplinkContractType extends Enum implements LocalizedEnum
{
    const NON = '0';
    const PAY_PAR_USE = '1';
    const FIX = '2';
}
