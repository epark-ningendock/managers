<?php

use App\Reservation;
use Carbon\Carbon;
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

        $x = 1;
        while ($x < 36) {

            $carbonDateTime = ($x === 1 ) ? Carbon::today()->startOfMonth() : Carbon::today()->subMonth($x-1) ;

            $days = 1;
            while( $days < 27 ) {
                $startedDayOfMonth = $carbonDateTime->startOfMonth()->addDay($days)->format('Y-m-d');

                factory(Reservation::class)->create([
                    'completed_date' => $startedDayOfMonth
                ]);

                $days++;
            }

            $days = ( $days === 27 ) ? 1 : $days;
            $x++;

        }

        $reservations = Reservation::all();

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
