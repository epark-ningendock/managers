<?php

use Illuminate\Database\Seeder;
use App\Option;
use Faker\Factory;

class ReservationsSeeder extends Seeder
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
        $reservations = factory(App\Reservation::class, 50)->create();

        $reservations->each(function ($reservation) use ($faker, $options) {
            $reservation_options = collect($faker->randomElements($options, 3)).map(function ($option) {
                $reservation_option = new ReservationOption();
                $reservation_option->fill([ 'option_id' => $option->id, 'option_price' => $option->price ]);
                return $reservation_option;
            })->toArray();
            $reservation->reservation_options()->saveMany($reservation_options);
        });
    }
}
