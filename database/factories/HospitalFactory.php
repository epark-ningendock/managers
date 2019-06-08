<?php

use Faker\Generator as Faker;

$factory->define(App\Hospital::class, function (Faker $faker) {
    return [
        'karada_dog_id' => $faker->randomNumber(),
        'code' => $faker->randomNumber(),
        'old_karada_dog_id' => $faker->randomNumber(),
        'name' => 'Hospital '.  $faker->company,
        'kana' => $faker->randomElement(['asc', 'desc']),
        'zip_code' => $faker->randomElement(['95050', '92053', '92050', '95250', '95250', '25050', '92050']),
        'pref' => $faker->randomElement(['0001','0003', '0004', '0005', '0008', '0006']),
        'district_code_id' => $faker->randomNumber(),
        'address1' => $faker->address,
        'address2' => $faker->address,
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'direction' => $faker->randomNumber(),
        'streetview_url' => $faker->url,
        'tel' => $faker->phoneNumber,
        'paycall' => $faker->phoneNumber,
        'fax' => $faker->phoneNumber,
        'email' => $faker->email,
        'url' => $faker->url,
        'consultation_note' => $faker->text(),
        'memo' => $faker->text(100),
        'business_hours' => $faker->time(),
        'rail1' => $faker->randomNumber(),
        'station1' => $faker->randomNumber(),
        'access1' => $faker->randomElement(['gate No1', 'entrance 2', 'platform 4', 'gate 9', 'Station 1']),
        'rail2' => $faker->randomNumber(),
        'station2' => $faker->randomNumber(),
        'access2' => $faker->randomElement(['gate No1', 'entrance 4', 'platform 5', 'gate 8', 'Station 1']),
        'rail3' => $faker->randomNumber(),
        'station3' => $faker->randomNumber(),
        'access3' => $faker->randomElement(['gate No 4', 'gate 11', 'entrance 4', 'entrance 8', 'platform 4', 'gate 9', 'Station 11']),
        'rail4' => $faker->randomNumber(),
        'station4' => $faker->randomNumber(),
        'access4' => $faker->randomElement(['gate No 4', 'entrance 8', 'platform 4', 'gate 9', 'Station 11']),
        'rail5' => $faker->randomNumber(),
        'station5' => $faker->randomNumber(),
        'access5' => $faker->randomElement(['gate No 4', 'entrance 8', 'platform 4', 'gate 9', 'Station 11']),
        'memo1' => $faker->text,
        'memo2' => $faker->text,
        'memo3' => $faker->text,
        'principal' => $faker->name,
        'principal_history' => $faker->text,
        'pv_count' => $faker->numberBetween(0, 1),
        'pvad' => $faker->numberBetween(0, 1),
        'is_pickup' => $faker->numberBetween(0, 1),
        'login_id' => $faker->userName,
        'login_psw' => bcrypt('123456'),
        'login_status' => $faker->numberBetween(0, 1),
        'status' => $faker->randomElement(['0','1','X']),
        'certified_facility' => $faker->numberBetween(0, 1),
        'free_area' => $faker->text,
        'search_word' => $faker->text,
        'plan_code' => $faker->numberBetween(1, 9),
        'hplink_contract_type' => $faker->numberBetween(0, 2),
        'hplink_count' => $faker->randomNumber(),
        'hplink_price' => $faker->randomNumber(),
        'created_id' => $faker->userName,
        'updated_id' => $faker->userName,
        'pre_account_flg' => $faker->numberBetween(0, 1),
        'pre_account_discount_rate' => $faker->randomNumber(),
        'pre_account_commission_rate' => $faker->randomElement(['0.5', '1.9', '3.9', '4.6']),
    ];
});
