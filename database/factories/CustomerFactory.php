<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'parent_customer_id' => $faker->numberBetween(1000, 4000),
        'member_number' => $faker->unique()->numberBetween(1000, 9000),
        'registration_card_number' => $faker->creditCardNumber,
        'name_seri' => $faker->firstName,
        'name_mei' => $faker->lastName,
        'name_kana_seri' => $faker->firstName,
        'name_kana_mei' => $faker->lastName,
        'tel' => $faker->phoneNumber,
        'email' => $faker->email,
        'postcode' => $faker->postcode,
        'prefecture_id' => $faker->numberBetween(1, 47),
        'address1' => $faker->city .$faker->streetAddress,
        'address2' => $faker->city .$faker->streetAddress,
        'sex' => $faker->randomElement(['M', 'F']),
        'birthday' => $faker->dateTimeBetween('-80 years', '-20years')->format('Y-m-d'),
        'memo' => '',
        'claim_count' => $faker->numberBetween(0, 10),
        'recall_count' => $faker->numberBetween(1000, 4000),
    ];
});
