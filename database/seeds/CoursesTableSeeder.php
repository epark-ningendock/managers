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
use App\Calendar;
use App\HospitalImage;
use App\ImageOrder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $hospital_images = HospitalImage::all()->groupBy('hospital_id');
        $image_orders = ImageOrder::all();
        $minors = MinorClassification::all();
        $options = Option::all()->groupBy('hospital_id');
        $calendars = Calendar::all()->groupBy('hospital_id');

        $courses = factory(Course::class, 50)->make();
        foreach($courses as $index => $course) {
            $hospital = $faker->randomElement($hospitals);
            $course->hospital_id = $hospital->id;
            $course->code = 'C'.$index.'H'.$hospital->id;
            $course->calendar_id = $faker->randomElement($calendars->get($hospital->id))->id;
            $course->save();

            factory(CourseQuestion::class, 5)->create([
                'course_id' => $course->id
            ]);

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

            $images = $faker->randomElements($hospital_images->get($hospital->id), 1);
            foreach ($images as $image) {
                factory(CourseImage::class)->create([
                    'course_id' => $course->id,
                    'hospital_image_id' => $image->id,
                    'image_order_id' => $faker->randomElement($image_orders)->id
                ]);
            }

            foreach ($options->get($hospital->id) as $option) {
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
        }

    }
}
