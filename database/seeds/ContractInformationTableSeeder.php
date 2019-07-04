<?php

use App\ContractInformation;
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
        factory(ContractInformation::class, 30)->create();
    }
}
