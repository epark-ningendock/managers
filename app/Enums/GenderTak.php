<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class GenderTak extends Enum implements LocalizedEnum
{
    const MALE = '1';
    const FEMALE = '2';
    const ALL = '3';
}
