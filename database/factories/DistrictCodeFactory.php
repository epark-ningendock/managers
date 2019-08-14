<?php

use App\MajorClassification;
use Faker\Generator as Faker;

$factory->define(App\DistrictCode::class, function (Faker $faker) {
    return [
//        '' => $faker->randomNumber(), //let's create this factory after majorclassification
        'name' => $faker->streetName,
        'order' => $faker->randomElement(['0', '1']),
        'is_icon' => $faker->randomElement(['0', '1']),
        'icon_name' => $faker->streetName
    ];
});

$factory->defineAs(\App\DistrictCode::class, 'with_major_class_id', function (Faker $faker) use ($factory) {
    $districtCode = $factory->raw(\App\DistrictCode::class);
    $majorClassification =MajorClassification::find(1);
    return array_merge($districtCode, ['major_classification_id' => $majorClassification->id]);
});
