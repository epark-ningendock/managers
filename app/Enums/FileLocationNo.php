<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class FileLocationNo extends Enum implements LocalizedEnum
{
    const TAB_CATEGORY_STAFF = 1;//タブ スタッフ
    const TAB_CATEGORY_FACILITY = 2;//タブ 設備
    const TAB_CATEGORY_INTERNAL = 3;//タブ 院内
    const TAB_CATEGORY_EXTERNAL = 4;//外観
    const TAB_CATEGORY_ANOTHER = 5;//その他
}
