<?php

use Faker\Generator as Faker;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;

$factory->define(App\HospitalMiddleClassification::class, function (Faker $faker) {
    $names = [
        'アクセスについて',
        '外国語対応',
        '女性対応',
        'お子様対応',
        '施設について',
        'プライバシー配慮'
    ];

    $result = [
        'name' => $names[rand(0, 5)],
        'status' => '1',
        'order' => 0,
        'is_icon' => $faker->randomElement(['0', '1'])
    ];

    if ($result['is_icon'] == '1') {
        $result['icon_name'] = $faker->userName;
    }

    return $result;
});

$factory->defineAs(HospitalMajorClassification::class, 'with_major', function (Faker $faker) use ($factory) {
    $middle = $factory->raw(HospitalMajorClassification::class);
    $major = factory(HospitalMajorClassification::class, 'with_type')->create();
    return array_merge($middle, ['major_classification_id' => $major->id]);
});
