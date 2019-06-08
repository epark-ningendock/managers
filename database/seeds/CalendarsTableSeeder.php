<?php

use Illuminate\Database\Seeder;
use App\Calendar;
use App\Hospital;

class CalendarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            factory(Calendar::class)->create([
                'hospital_id'=> $hospital->id
            ]);
        }
    }
}
