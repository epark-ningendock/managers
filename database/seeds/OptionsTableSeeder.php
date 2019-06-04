<?php

use Illuminate\Database\Seeder;
use App\Hospital;
use App\Option;

class OptionsTableSeeder extends Seeder
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
            factory(Option::class)->create([
                'hospital_id'=> $hospital->id
            ]);
        }
    }
}
