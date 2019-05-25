<?php

use Faker\Generator as Faker;
use App\CalendarDay;

$factory->define(CalendarDay::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTimeBetween('now', '+1 years'),
        'holiday_flg' => $faker->randomElement([0, 1]),
        'reservation_flg'=> $faker->randomElement([0, 1]),
        'reservation_flames' => $faker->numberBetween(1, 100),
        'reservation_id' => $faker->numberBetween(1, 100)
    ];
});
