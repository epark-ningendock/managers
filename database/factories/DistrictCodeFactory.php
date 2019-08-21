<?php

use App\MajorClassification;
use Faker\Generator as Faker;

$factory->define(App\DistrictCode::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
        'kana' => $faker->streetName,
    ];
});
