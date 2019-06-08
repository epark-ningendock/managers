<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'parent_customer_id' => $faker->numberBetween(1000, 4000),
        'member_number' => $faker->unique()->numberBetween(1000, 9000),
        'registration_card_number' => $faker->creditCardNumber,
        'name' => $faker->name,
        'name_kana' => $faker->lastKanaName. $faker->firstKanaName,
        'tel' => $faker->phoneNumber,
        'email' => $faker->email,
        'postcode' => $faker->postcode,
        'prefecture_id' => $faker->numberBetween(1, 47),
        'address' => $faker->city .$faker->streetAddress,
        'sex' => $faker->randomElement(['M', 'F']),
        'birthday' => $faker->dateTimeBetween('-80 years', '-20years')->format('Ymd'),
        'memo' => '',
        'claim_count' => $faker->numberBetween(0, 10),
        'recall_count' => $faker->numberBetween(1000, 4000),
    ];
});
