<?php

use Faker\Generator as Faker;
use App\HospitalEmailSetting;

$factory->define(HospitalEmailSetting::class, function (Faker $faker) {
    return [
        'hospital_id' => $faker->unique()->numberBetween(1, 50),
        'in_hospital_email_reception_flg' => $faker->randomElement([0, 1]),
        'in_hospital_confirmation_email_reception_flg' => $faker->randomElement([0, 1]),
        'in_hospital_change_email_reception_flg' => $faker->randomElement([0, 1]),
        'in_hospital_cancellation_email_reception_flg' => $faker->randomElement([0, 1]),
        'email_reception_flg' => $faker->randomElement([0, 1]),
        'in_hospital_reception_email_flg' => $faker->randomElement([0, 1]),
        'web_reception_email_flg' => $faker->randomElement([0, 1]),
        'reception_email1' => $faker->unique()->safeEmail,
        'reception_email2' => $faker->unique()->safeEmail,
        'reception_email3' => $faker->unique()->safeEmail,
        'reception_email4' => $faker->unique()->safeEmail,
        'reception_email5' => $faker->unique()->safeEmail,
        'billing_email1' => $faker->unique()->safeEmail,
        'billing_email2' => $faker->unique()->safeEmail,
        'billing_email3' => $faker->unique()->safeEmail,
        'billing_fax_number' => $faker->numberBetween(33232323, 93939393939) . '@faxmail.com',
        'epark_in_hospital_reception_mail_flg' => $faker->randomElement([0, 1]),
        'epark_web_reception_email_flg' => $faker->randomElement([0, 1]),
    ];
});
