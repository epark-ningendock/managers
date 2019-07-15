<?php

use Faker\Generator as Faker;
use App\HospitalDetail;
use App\Hospital;
use App\HospitalMinorClassification;

$factory->define(App\HospitalDetail::class, function (Faker $faker) {
    return [
        'inputstring' => $faker->text
    ];
});

$factory->defineAs(HospitalDetail::class, 'with_minor', function (Faker $faker) use ($factory) {
    $hospital = factory(Hospital::class)->create();
    $minor = factory(HospitalMinorClassification::class, 'with_middle')->create();

    return array_merge($detail, [
        'minor_classification_id' => $minor->id
    ]);
});
