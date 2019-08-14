<?php
use Faker\Generator as Faker;
use \App\Course;
use App\Calendar;
use App\TaxClass;
use App\Hospital;

$factory->define(Course::class, function (Faker $faker) {
    $result = [
        'name' => $faker->name,
        'web_reception' => $faker->randomElement(['0', '1']),
        'is_price' => $faker->randomElement([0, 1]),
        'is_price_memo' => $faker->randomElement([0, 1]),
        'order' => $faker->randomElement(range(1, 100)),
        'is_category' => $faker->randomElement([1, 2]),
        'course_point' => $faker->text,
        'course_notice' => $faker->text,
        'reception_start_date' => $faker->randomElement(range(0, 12)) * 1000 + $faker->randomElement(range(0, 31)),
        'reception_end_date' => $faker->randomElement(range(0, 12)) * 1000 + $faker->randomElement(range(0, 31)),
        'reception_acceptance_date' => $faker->randomElement(range(0, 12)) * 1000 + $faker->randomElement(range(0, 31)),
        'cancellation_deadline' => $faker->randomElement(range(1, 31)),
        'is_pre_account' => $faker->randomElement([0, 1])
    ];
    if ($result['is_price'] == 1) {
        $result['price'] = $faker->randomDigit;
    }
    if ($result['is_price_memo'] == 1) {
        $result['price_memo'] = $faker->word;
    }
    return $result;
});

$factory->defineAs(Course::class, 'with_all', function (Faker $faker) use ($factory) {
    $hospital = factory(Hospital::class)->create();
    $calendar = factory(Calendar::class)->create(['hospital_id' => $hospital->id]);
    $tax_class = factory(TaxClass::class)->create();
    $course = $factory->raw(Course::class);
    return array_merge($course, [
        'hospital_id' => $hospital->id,
        'calendar_id' => $calendar->id,
        'tax_class' => $tax_class->id,
        'code' => 'C1H' . $hospital->id
    ]);
});

$factory->defineAs(Course::class, 'for_seeder', function (Faker $faker) use ($factory) {
    $course = $factory->raw(Course::class);
    return array_merge($course, [
        'code' => 'C1H' . $hospital->id
    ]);
});
