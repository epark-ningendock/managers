<?php

use App\Hospital;
use Carbon\Carbon;
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
		    $x = 1;
		    while ($x < 36) {
			    $carbonDateTime = ($x === 1 ) ? Carbon::today() : Carbon::today()->subMonth($x) ;
			    $startedDayOfMonth = $carbonDateTime->startOfMonth()->format('Y-m-d');
			    $endDayOfMonth = $carbonDateTime->endOfMonth()->format('Y-m-d');

			    factory(\App\HospitalPlan::class)->create([
			    	'hospital_id' => $hospital->id,
				    'from' => $startedDayOfMonth,
				    'to' => $endDayOfMonth,
			    ]);

			    $x++;
		    }
	    }

    }
}
