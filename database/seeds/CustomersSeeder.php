<?php

use App\Customer;
use App\Reservation;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Customer::class, 50)->create()->each(function ($customer, $index) {
            factory(Reservation::class, 3)->create([
                'customer_id' => $customer->id
            ]);
        });
    }
}
