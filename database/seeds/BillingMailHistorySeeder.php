<?php

use App\BillingMailHistory;
use App\Hospital;
use Illuminate\Database\Seeder;

class BillingMailHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitals = Hospital::all();
        foreach($hospitals as $hospital ) {
            factory(BillingMailHistory::class)->create(['hospital_id' => $hospital->id]);
        }
    }
}
