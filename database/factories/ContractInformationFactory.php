<?php

use Faker\Generator as Faker;

$factory->define(App\ContractInformation::class, function (Faker $faker) {
    return [
        'contractor_name_kana' => $faker->name,
        'contractor_name' => $faker->name,
        'application_date' => $faker->dateTime,
        'billing_start_date' => $faker->dateTime,
        'cancellation_date' => $faker->dateTime,
        'representative_name_kana' => $faker->name,
        'representative_name' => $faker->name,
        'postcode' => $faker->postcode,
        'address' => $faker->address,
        'tel' => $faker->phoneNumber,
        'fax' => $faker->phoneNumber,
        'karada_dog_id' => $faker->randomNumber(),
        'code' => $faker->randomNumber(),
        'old_karada_dog_id' => $faker->randomNumber(),
        'hospital_staff_id' => factory(\App\HospitalStaff::class)->create()->id,
    ];
});
