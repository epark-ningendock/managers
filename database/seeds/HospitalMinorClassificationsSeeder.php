<?php

use Illuminate\Database\Seeder;
use App\HospitalMinorClassification;

class HospitalMinorClassificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(HospitalMinorClassification::class, 'with_middle', 50)->create();
    }
}
