<?php

use Faker\Generator as Faker;
use App\MinorClassification;
use App\MiddleClassification;
use App\MajorClassification;

$factory->define(MinorClassification::class, function (Faker $faker) {
    $result = [
        'name' => $faker->userName,
        'is_fregist' => $faker->randomElement([0, 1]),
        'status' => '1',
        'order' => 0,
        'max_length' => $faker->numberBetween(50, 100),
        'is_icon' => $faker->randomElement(['0', '1'])
    ];

    if ($result['is_icon'] == '1') {
        $result['icon_name'] = $faker->userName;
    }

    return $result;
});

$factory->defineAs(MinorClassification::class, 'with_major_middle', function (Faker $faker) use ($factory) {
    $minor = $factory->raw(MinorClassification::class);
    $middle = factory(MiddleClassification::class, 'with_major')->create();
    return array_merge($minor, [
        'middle_classification_id' => $middle->id,
        'major_classification_id' => $middle->major_classification->id
    ]);
});
