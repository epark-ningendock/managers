<?php

use Faker\Generator as Faker;
use App\FeeRate;

$factory->define(FeeRate::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['0', '1']),
        'rate' => $faker->randomDigit,
        'from_date' => $faker->date()
    ];
});
