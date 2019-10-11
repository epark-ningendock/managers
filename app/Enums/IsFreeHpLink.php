<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class IsFreeHpLink extends Enum implements LocalizedEnum
{
    const FEE = '0';
    const FREE = '1';
}
