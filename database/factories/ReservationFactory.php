<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use App\Reservation;
use App\Hospital;
use App\Customer;
use App\Course;

$factory->define(Reservation::class, function (Faker $faker) {

    $reservation_status = $faker->randomElement([1, 2, 3, 4]);

    if ( $reservation_status == 4 ) {
        $site_code = 'HP';
        $fee = 0;
    } else {
        $site_code = $faker->randomElement(['HP', $faker->shuffle('abcdefghijklmnopqrstuvwxyz')]);
        $fee = $faker->numberBetween(0, 5000);
    }

    return [
        'hospital_id' => $faker->numberBetween(1, 50),
        'course_id' => $faker->numberBetween(1, 50),
        'reservation_date' => $faker->date('Y-m-d', 'now'),
        'start_time_hour' => sprintf('%02d', $faker->numberBetween(0, 23)),
        'start_time_min' => sprintf('%02d', $faker->numberBetween(0, 59)),
        'end_time_hour' => sprintf('%02d', $faker->numberBetween(0, 23)),
        'end_time_min' => sprintf('%02d', $faker->numberBetween(0, 59)),
        'channel' => $faker->randomElement([0, 1, 2]),
        'is_billable' => $faker->randomElement(['0', '1']),
        'reservation_status' => $reservation_status,
        'completed_date' => $faker->dateTimeBetween('-200 days', now()),
        'cancel_date' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
        'user_message' => $faker->sentence(10),
        'site_code' => $site_code,
        'customer_id' => null,
        'epark_member_id' => null,
        'member_number' => $faker->numberBetween(1, 50),
        'terminal_type' => $faker->randomElement([1, 2, 3, 4, 5]),
        'time_selected' => $faker->randomElement([0, 1]),
        'is_repeat' => $faker->randomElement([0, 1]),
        'is_representative' => $faker->randomElement([0, 1]),
        'tax_included_price' => $faker->numberBetween(1000, 8000),
        'adjustment_price' => null,
        'tax_rate' => $faker->numberBetween(1, 30),
        'second_date' => null,
        'third_date' => null,
        'is_choose' => null,
        'campaign_code' => null,
        'tel_timezone' => null,
        'insurance_assoc_id' => null,
        'insurance_assoc' => null,
        'mail_type' => $faker->randomElement(['0', '1']),
        'cancelled_appoint_code' => null,
        'claim_month' => $faker->randomElement([Carbon::now()->addMonth()->format('Y/m'),  Carbon::now()->subMonth()->format('Y/m'), Carbon::now()->format('Y/m')]),
        'is_payment' => $faker->randomElement(['0', '1']),
        'payment_status' => $faker->randomElement(['1', '2', '3', '9']),
        'trade_id' => $faker->shuffle('abcdefghijklmnopqrstuvwxyz', 10),
        'order_id' => null,
        'settlement_price' => $faker->numberBetween(1000, 4000),
        'payment_method' => $faker->randomElement(['現金', 'クレジットカード']),
        'cashpo_used_price' => $faker->numberBetween(1000, 4000),
        'amount_unsettled' => $faker->numberBetween(1000, 4000),
        'reservation_memo' => $faker->sentence(10),
        'todays_memo' => $faker->sentence(10),
        'internal_memo' => $faker->sentence(10),
        'acceptance_number' => $faker->numberBetween(1000, 4000),
        'y_uid' => null,
        'fee' => $fee,
        'lock_version' => 1,
        'fee_rate' => $faker->numberBetween(1, 30),
	    'is_free_hp_link' => 1,
    ];
});


$factory->defineAs(Reservation::class, 'with_all', function (Faker $faker) use ($factory) {
    $reservation = $factory->raw(Reservation::class);
    $hospital = factory(Hospital::class)->create();
    $customer = factory(Customer::class)->create();
    $course = factory(Course::class, 'with_all')->create();
    return array_merge($reservation, [
        'hospital_id' => $hospital->id,
        'customer_id' => $customer->id,
        'applicant_name' => "$customer->family_name $customer->first_name",
        'applicant_name_kana' => "$customer->family_name_kana $customer->first_name_kana",
        'applicant_tel' => $customer->tel,
        'course_id' => $course->id
    ]);
});
