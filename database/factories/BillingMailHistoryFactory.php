<?php

use App\BillingMailHistory;
use App\Hospital;
use Faker\Generator as Faker;

$factory->define(BillingMailHistory::class, function (Faker $faker) {
    return [
        'hospital_id' => factory(Hospital::class)->create()->id,
        'to_address1' => $faker->email,
        'to_address2' => $faker->email,
        'to_address3' => $faker->email,
        'cc_name' => $faker->name,
        'fax' => $faker->phoneNumber,
        'mail_type' => $faker->numberBetween(1, 2)
    ];
});
