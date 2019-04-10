<?php

use Illuminate\Database\Seeder;

class HospitalStaffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    factory(App\HospitalStaff::class, 50)->create();
    }
}
