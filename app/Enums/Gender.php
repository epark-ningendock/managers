<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class Gender extends Enum implements LocalizedEnum
{
    const MALE = 'M';
    const FEMALE = 'F';
}
