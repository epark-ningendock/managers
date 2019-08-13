<?php

use App\ContractInformation;
use Illuminate\Database\Seeder;
use App\Hospital;

class ContractInformationTableSeeder extends Seeder
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
            factory(ContractInformation::class)->create([
                'hospital_id' => $hospital->id
            ]);
        }
    }
}
