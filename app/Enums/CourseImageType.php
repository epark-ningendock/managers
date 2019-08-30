<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class CourseImageType extends Enum implements LocalizedEnum
{
    const Main = '0';
    const Pc = '1';
    const Sp = '2';
}
