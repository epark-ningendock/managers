<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class NickUse extends Enum implements LocalizedEnum
{
    const USE = '1';
    const NON_USE = 'X';
}
