<?php

use Faker\Generator as Faker;
use App\Reservation;
use App\Hospital;
use App\Customer;
use App\Course;

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'hospital_id' => $faker->numberBetween(1, 50),
        'course_id' => $faker->numberBetween(1, 50),
        'reservation_date' => $faker->date('Y-m-d', 'now'),
        'start_time_hour' => sprintf('%02d', $faker->numberBetween(0, 23)),
        'start_time_min' => sprintf('%02d', $faker->numberBetween(0, 59)),
        'end_time_hour' => sprintf('%02d', $faker->numberBetween(0, 23)),
        'end_time_min' => sprintf('%02d', $faker->numberBetween(0, 59)),
        'channel' => $faker->randomElement([0, 1, 2]),
        'reservation_status' => $faker->randomElement([1, 2, 3, 4]),
        'completed_date' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
        'cancel_date' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
        'user_message' => $faker->sentence(10),
        'site_code' => $faker->shuffle('abcdefghijklmnopqrstuvwxyz'),
        'customer_id' => null,
        'epark_member_id' => null,
        'member_number' => $faker->numberBetween(1, 50),
        'terminal_type' => $faker->randomElement([1, 2, 3, 4, 5]),
        'time_selected' => $faker->randomElement([0, 1]),
        'is_repeat' => $faker->randomElement([0, 1]),
        'is_representative' => $faker->randomElement([0, 1]),
        'timezone_pattern_id' => $faker->shuffle('abcdefghijklmnopqrstuvwxyz'),
        'timezone_id' => $faker->shuffle('abcdefghijklmnopqrstuvwxyz', 10),
        'order' => $faker->shuffle('123', 3),
        'tax_included_price' => null,
        'adjustment_price' => null,
        'tax_rate' => null,
        'second_date' => null,
        'third_date' => null,
        'is_choose' => null,
        'campaign_code' => null,
        'tel_timezone' => null,
        'insurance_assoc_id' => null,
        'insurance_assoc' => null,
        'mail_type' => $faker->randomElement(['0', '1']),
        'cancelled_appoint_code' => null,
        'is_billable' => null,
        'claim_month' => null,
        'is_payment' => $faker->randomElement(['0', '1']),
        'payment_status' => $faker->randomElement(['1', '2', '3', '9']),
        'trade_id' => $faker->shuffle('abcdefghijklmnopqrstuvwxyz', 10),
        'order_id' => null,
        'settlement_price' => $faker->numberBetween(1000, 4000),
        'payment_method' => $faker->randomElement(['現金', 'クレジットカード']),
        'cashpo_used_price' => null,
        'amount_unsettled' => $faker->numberBetween(1000, 4000),
        'reservation_memo' => $faker->sentence(10),
        'todays_memo' => $faker->sentence(10),
        'internal_memo' => $faker->sentence(10),
        'acceptance_number' => $faker->numberBetween(1000, 4000),
        'y_uid' => null,
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
        'course_id' => $course->id
    ]);
});
