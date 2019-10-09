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
        factory(ContractPlan::class)->create([
            'plan_code' => 'Y001',
            'monthly_contract_fee' => 10000,
        ]);
        factory(ContractPlan::class)->create([
            'plan_code' => 'Y002',
            'monthly_contract_fee' => 20000,
        ]);
        factory(ContractPlan::class)->create([
            'plan_code' => 'Y003',
            'monthly_contract_fee' => 50000,
        ]);
        factory(ContractPlan::class)->create([
            'plan_code' => 'Y004',
            'monthly_contract_fee' => 75000,
        ]);
        factory(ContractPlan::class)->create([
            'plan_code' => 'Y005',
            'monthly_contract_fee' => 100000,
        ]);
    }
}
