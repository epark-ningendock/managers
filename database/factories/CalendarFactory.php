<?php

use Faker\Generator as Faker;
use App\Calendar;

$factory->define(Calendar::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'is_calendar_display' => 1
    ];
});
