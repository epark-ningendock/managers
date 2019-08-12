<?php

use App\Customer;
use App\Reservation;
use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Option;
use App\ReservationOption;
use App\Course;
use App\ReservationAnswer;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $options = Option::all();
        $courses = Course::all();

        factory(Customer::class, 50)->create()->each(function ($customer, $index) use ($faker, $options, $courses) {
            $reservations = factory(Reservation::class, 3)->create([
                'customer_id' => $customer->id,
                'course_id' => $faker->randomElement($courses)->id
            ]);

            $reservations->each(function ($reservation) use ($faker, $options) {
                //reservation option
                $reservation_options = collect($faker->randomElements($options, 3))->map(function ($option) {
                    $reservation_option = new ReservationOption();
                    $reservation_option->fill(['option_id' => $option->id, 'option_price' => $option->price]);
                    return $reservation_option;
                });
                $reservation->reservation_options()->saveMany($reservation_options);

                //reservation answer
                $reservation_answers = $reservation->course->course_questions->map(function ($question) use ($reservation, $faker) {
                    $reservation_answer = factory(ReservationAnswer::class)->make();
                    $reservation_answer->fill([
                        'reservation_id' => $reservation->id,
                        'course_id' => $question->course_id,
                        'course_question_id' => $question->id,
                        'question_title' => $question->question_title,
                        'question_answer01' => $question->answer01,
                        'question_answer02' => $question->answer02,
                        'question_answer03' => $question->answer03,
                        'question_answer04' => $question->answer04,
                        'question_answer05' => $question->answer05,
                        'question_answer06' => $question->answer06,
                        'question_answer07' => $question->answer07,
                        'question_answer08' => $question->answer08,
                        'question_answer09' => $question->answer09,
                        'question_answer10' => $question->answer10
                    ]);
                    return $reservation_answer;
                });
                $reservation->reservation_answers()->saveMany($reservation_answers);
            });
        });
    }
}
