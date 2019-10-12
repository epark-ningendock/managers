<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ImageGroupNumber extends Enum implements LocalizedEnum
{
    //imge_group_number
    const IMAGE_GROUP_FACILITY_MAIN = 1;//施設メイン
    const IMAGE_GROUP_FACILITY_SUB = 2;//施設サブ
    const IMAGE_GROUP_TOP = 3;//TOP（使われてない気がする）
    const IMAGE_GROUP_MAP = 4;//地図
    const IMAGE_GROUP_SPECIALITY = 5;// こだわり
    const IMAGE_GROUP_INTERVIEW = 6;// インタビュー
    const IMAGE_GROUP_STAFF = 7;// スタッフ
    const IMAGE_GROUP_TAB = 8;// 写真タブ
    const IMAGE_GROUP_SELECT = 9;// 写真タブ選択写真
}
