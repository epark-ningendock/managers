<?php

use Faker\Generator as Faker;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;

$factory->define(App\HospitalMinorClassification::class, function (Faker $faker) {
    $result = [
        'name' => $faker->userName,
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
    echo('this1');
    return array_merge($minor, ['middle_classification_id' => $middle->id]);
});
