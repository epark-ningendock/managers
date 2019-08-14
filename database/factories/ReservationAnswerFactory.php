<?php

use Faker\Generator as Faker;

$factory->define(App\ReservationAnswer::class, function (Faker $faker) {
    return [
        'answer01' => $faker->randomElement(['0', '1']),
        'answer02' => $faker->randomElement(['0', '1']),
        'answer03' => $faker->randomElement(['0', '1']),
        'answer04' => $faker->randomElement(['0', '1']),
        'answer05' => $faker->randomElement(['0', '1']),
        'answer06' => $faker->randomElement(['0', '1']),
        'answer07' => $faker->randomElement(['0', '1']),
        'answer08' => $faker->randomElement(['0', '1']),
        'answer09' => $faker->randomElement(['0', '1']),
        'answer10' => $faker->randomElement(['0', '1'])
    ];
});
