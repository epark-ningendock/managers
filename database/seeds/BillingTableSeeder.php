<?php

use App\Billing;
use App\Hospital;
use Carbon\Carbon;
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

	        $x = 1;
	        while ($x < 36) {
                $month_years = ($x == 1) ? Carbon::today() : Carbon::today()->subMonth($x-1);
                $monthList = $month_years->format('Ym');
		        factory(Billing::class)->create([
			        'hospital_id' => $hospital->id,
			        'billing_month' => $monthList,
		        ]);
		        $x++;
	        }
        }
    }
}
