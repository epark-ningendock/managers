<?php

use Faker\Generator as Faker;
use App\Calendar;

$factory->define(Calendar::class, function (Faker $faker) {
    $names = [
        '人間ドックカレンダー',
        '脳ドックカレンダー',
        'PET検診カレンダー',
        '心臓検診カレンダー',
        '大腸がん検診カレンダー',
        '肺がん検診カレンダー',
        '健康診断カレンダー',
    ];

    return [
        'name' => $names[rand(0, count($names) - 1)],
        'is_calendar_display' => 1
    ];
});
