<?php

use Faker\Generator as Faker;
use App\EmailTemplate;

$factory->define(EmailTemplate::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'text' => $faker->name,
        'hospital_id' => $faker->numberBetween(1, 50)
    ];
});
