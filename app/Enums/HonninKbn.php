<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class HonninKbn extends Enum implements LocalizedEnum
{
    const PERSON = '1';
    const FAMILY = '2';
    const ALL = '3';
}
