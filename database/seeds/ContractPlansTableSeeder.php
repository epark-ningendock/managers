<?php

use Illuminate\Database\Seeder;
use App\ContractPlan;

class ContractPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ContractPlan::class, 10)->create();
    }
}
