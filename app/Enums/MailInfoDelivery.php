<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class MailInfoDelivery extends Enum implements LocalizedEnum
{
    const RECEIVE = '1';
    const NOT_RECEIVE = 'X';
}
