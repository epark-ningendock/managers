<?php

use App\Customer;
use App\Reservation;
use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Option;
use App\ReservationOption;

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

        factory(Customer::class, 50)->create()->each(function ($customer, $index) use($faker, $options) {
            $reservations = factory(Reservation::class, 3)->create([
                'customer_id' => $customer->id
            ]);

            $reservations->each(function($reservation) use ($faker, $options){
                $reservation_options = collect($faker->randomElements($options, 3))->map(function($option){
                    $reservation_option = new ReservationOption();
                    $reservation_option->fill([ 'option_id' => $option->id, 'option_price' => $option->price ]);
                    return $reservation_option;
                });
                $reservation->reservation_options()->saveMany($reservation_options);
            });
        });
    }
}
