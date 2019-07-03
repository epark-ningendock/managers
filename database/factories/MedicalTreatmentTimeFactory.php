<?php

use App\Hospital;
use Faker\Generator as Faker;

$factory->define(App\MedicalTreatmentTime::class, function (Faker $faker) {
    return [
        'hospital_id' => factory(Hospital::class)->create()->id,
        'start' => $faker->time('H:i'),
        'end' => $faker->time('H:i'),
        'mon' => $faker->numberBetween(1, 10),
        'tue' => $faker->numberBetween(1, 10),
        'wed' => $faker->numberBetween(1, 10),
        'thu' => $faker->numberBetween(1, 10),
        'fri' => $faker->numberBetween(1, 10),
        'sat' => $faker->numberBetween(1, 10),
        'sun' => $faker->numberBetween(1, 10),
        'hol' => $faker->numberBetween(1, 10),
        'status' => $faker->numberBetween(0,1),
    ];
});
