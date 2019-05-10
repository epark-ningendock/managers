<?php

use Illuminate\Database\Seeder;
use App\Course;
use App\CourseDetail;
use App\Hospital;
use Faker\Factory;
use App\CourseQuestion;
use App\CourseImage;
use App\MinorClassification;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $hospitals = Hospital::all();
        $minors = MinorClassification::all();
        factory(Course::class, 50)->make()->each(function($course, $index) use($faker, $hospitals, $minors) {
            $hospital = $faker->randomElement($hospitals);
            $course->hospital_id = $hospital->id;
            $course->code = 'C'.$index.'H'.$hospital->id;
            $course->save();

            $minor = $faker->randomElement($minors);
            factory(CourseDetail::class)->create([
                'course_id' => $course->id,
                'minor_classification_id' => $minor->id,
                'middle_classification_id' => $minor->middle_classification_id,
                'major_classification_id' => $minor->major_classification_id
            ]);



            factory(CourseQuestion::class, 5)->create([
                'course_id' => $course->id
            ]);

            factory(CourseImage::class, 5)->create([
                'course_id' => $course->id
            ]);


        });
    }
}
