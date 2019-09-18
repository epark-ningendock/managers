<?php

use App\Hospital;
use Illuminate\Database\Seeder;

class HospitalPlanTableSeeder extends Seeder
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
		    factory(\App\HospitalPlan::class, 50)->create(['hospital_id' => $hospital->id]);
	    }

    }
}
