<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ReceptionEmailSetting extends Enum implements LocalizedEnum
{
    const NOT_ACCEPT = 0;
    const ACCEPT = 1;
}
