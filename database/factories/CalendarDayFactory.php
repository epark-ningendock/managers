<?php

use Faker\Generator as Faker;
use App\CalendarDay;

$factory->define(CalendarDay::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTimeBetween('now', '+1 years'),
        'is_holiday' => $faker->randomElement([0, 1]),
        'is_reservation_acceptance'=> $faker->randomElement([0, 1]),
        'reservation_frames' => $faker->numberBetween(1, 100)
    ];
});
