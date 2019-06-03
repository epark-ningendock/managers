<?php

use Faker\Generator as Faker;
use App\MiddleClassification;
use \App\MajorClassification;

$factory->define(MiddleClassification::class, function (Faker $faker) {
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

$factory->defineAs(MiddleClassification::class, 'with_major', function (Faker $faker) use ($factory) {
    $middle = $factory->raw(MiddleClassification::class);
    $major = factory(MajorClassification::class, 'with_type')->create();
    return array_merge($middle, ['major_classification_id' => $major->id]);
});
