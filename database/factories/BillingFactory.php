<?php

use App\Hospital;
use Faker\Generator as Faker;

$factory->define(App\Billing::class, function (Faker $faker) {
    return [
        'hospital_id' => factory(Hospital::class)->create()->id,
        'contract_plan_id' => factory(Hospital::class)->create()->id,
        'from' => $faker->dateTime('+360 days'),
        'to' => $faker->dateTime('+360 days'),
    ];
});
