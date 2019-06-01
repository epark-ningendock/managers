<?php

use Illuminate\Database\Seeder;
use App\Course;
use App\CourseDetail;
use App\Hospital;
use Faker\Factory;
use App\CourseQuestion;
use App\CourseImage;
use App\MinorClassification;
use App\CourseOption;
use App\Option;
use App\TaxClass;
use App\Calendar;
use App\HospitalImage;
use App\ImageOrder;

class CoursesTableSeeder extends Seeder
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
        $hospital_images = HospitalImage::all();
        $image_orders = ImageOrder::all();
        $minors = MinorClassification::all();
        $options = Option::all();
        $tax_classes = TaxClass::all();
        $calendars = Calendar::all();
        factory(Course::class, 50)->make()->each(function ($course, $index) use ($faker, $hospitals, $minors, $options, $tax_classes, $calendars, $hospital_images, $image_orders) {
            $hospital = $faker->randomElement($hospitals);
            $course->hospital_id = $hospital->id;
            $course->code = 'C'.$index.'H'.$hospital->id;
            $course->tax_class = $faker->randomElement($tax_classes)->id;
            $course->calendar_id = $faker->randomElement($calendars)->id;
            $course->save();


            foreach ($minors as $minor) {
                $detail = [
                    'course_id' => $course->id,
                    'minor_classification_id' => $minor->id,
                    'middle_classification_id' => $minor->middle_classification_id,
                    'major_classification_id' => $minor->major_classification_id,
                ];
                if ($minor->is_fregist == '1') {
                    $detail['inputstring'] = null;
                    $detail['select_status'] = $faker->randomElement([0, 1]);
                } else {
                    $detail['inputstring'] = $faker->text($minor->max_length);
                    $detail['select_status'] = null;
                }
                factory(CourseDetail::class)->create($detail);
            }


            factory(CourseQuestion::class, 5)->create([
                'course_id' => $course->id
            ]);

            $images = $faker->randomElements($hospital_images, 5);
            foreach ($images as $image) {
                factory(CourseImage::class)->create([
                    'course_id' => $course->id,
                    'hospital_image_id' => $image->id,
                    'image_order_id' => $faker->randomElement($image_orders)->id
                ]);
            }

            foreach ($options as $option) {
                //random option
                if ($faker->randomElement([0, 1]) == 1) {
                    $course_option = new CourseOption();
                    $course_option->fill([
                        'course_id' => $course->id,
                        'option_id' => $option->id
                    ]);
                    $course_option->save();
                }
            }
        });
    }
}
