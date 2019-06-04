<?php

use Faker\Generator as Faker;
use App\TaxClass;
use Carbon\Carbon;

$factory->define(TaxClass::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'rate' => $faker->numberBetween(0, 10),
        'life_time_from' => Carbon::createFromDate(2014, 4, 1),
        'life_time_to' => Carbon::createFromDate(2037, 12, 31)
    ];
});
