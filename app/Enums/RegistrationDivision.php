<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class RegistrationDivision extends Enum implements LocalizedEnum
{
    const TEXTAREA = '0';
    const CHECK_BOX = '1';
    const CHECK_BOX_AND_TEXT = '2';
}
