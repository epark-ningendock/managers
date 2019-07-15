<?php

use App\MajorClassification;
use App\Prefecture;
use Faker\Generator as Faker;

$factory->define(App\DistrictCode::class, function (Faker $faker) {
    return [
        'district_code' => $faker->numberBetween(0000000,9999999),
        'prefecture_id' => factory(Prefecture::class)->create()->id,
        'name' => $faker->streetName,
        'kana' => $faker->streetName,
        'status' => $faker->randomElement(['1', 'X'])
    ];
});
