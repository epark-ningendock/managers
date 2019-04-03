<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FacilityStaffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    factory(App\FacilityStaff::class, 50)->create();
    }
}
