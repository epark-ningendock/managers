<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $this->call([
            StaffsTableSeeder::class,
            StaffAuthsTableSeeder::class,
	        HospitalStaffsTableSeeder::class,
        ]);

        factory(App\Staff::class, 50)->create();

    }
}
