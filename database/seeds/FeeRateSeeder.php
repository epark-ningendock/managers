<?php

use Illuminate\Database\Seeder;
use App\Hospital;
use App\FeeRate;
use Carbon\Carbon;

class FeeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitals = Hospital::all();

        foreach($hospitals as $hospital) {
            factory(FeeRate::class)->create([
               'hospital_id' => $hospital->id,
                'from_date' => Carbon::today()
            ]);
        }
    }
}
