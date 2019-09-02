<?php

use App\Billing;
use App\Hospital;
use Illuminate\Database\Seeder;

class BillingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitals = Hospital::all();

        foreach( $hospitals as $hospital ) {
            factory(Billing::class)->create(['hospital_id' => $hospital->id]);
        }
    }
}
