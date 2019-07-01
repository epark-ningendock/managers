<?php

use Faker\Generator as Faker;
use App\HospitalMajorClassification;

$factory->define(App\HospitalMajorClassification::class, function (Faker $faker) {
    $result = [
        'name' => $faker->userName,
        'status' => '1',
        'order' => 0,
        'is_icon' => $faker->randomElement(['0', '1'])
    ];

    if ($result['is_icon'] == '1') {
        $result['icon_name'] = $faker->userName;
    }
    echo('this3');
    return $result;
});
