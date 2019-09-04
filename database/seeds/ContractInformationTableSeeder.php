<?php

use App\ContractInformation;
use App\Hospital;
use Illuminate\Database\Seeder;

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
            ]);
        }
    }
}
