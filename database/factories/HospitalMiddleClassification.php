<?php

use Faker\Generator as Faker;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;

$factory->define(App\HospitalMiddleClassification::class, function (Faker $faker) {
    $result = [
        'name' => $faker->userName,
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
    echo('this2');
    return array_merge($middle, ['major_classification_id' => $major->id]);
});
