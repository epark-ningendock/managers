<?php

use Faker\Generator as Faker;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;

$factory->define(App\HospitalMinorClassification::class, function (Faker $faker) {
    $names = [
        '駐車場あり',
        'アクセスについて',
        'その他',
        '英語',
        '中国語',
        '韓国語',
        '女性専用施設あり',
        'レディースデーあり',
        'パウダールームあり',
        'キッズスペース'
    ];

    $result = [
        'name' => $names[rand(0, 9)],
        'is_fregist' => $faker->randomElement([0, 1]),
        'status' => '1',
        'order' => 0,
        'is_icon' => $faker->randomElement(['0', '1'])
    ];

    if ($result['is_icon'] == '1') {
        $result['icon_name'] = $faker->userName;
    }

    return $result;
});

$factory->defineAs(HospitalMinorClassification::class, 'with_middle', function (Faker $faker) use ($factory) {
    $minor = $factory->raw(HospitalMinorClassification::class);
    $middle = factory(HospitalMiddleClassification::class, 'with_major')->create();
    return array_merge($minor, ['middle_classification_id' => $middle->id]);
});
