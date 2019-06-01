<?php

use Illuminate\Database\Seeder;
use \App\Staff;
use \App\StaffAuth;

class StaffAuthsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff::all()->each(function ($staff) {
            factory(StaffAuth::class)->create(["staff_id" => $staff->id]);
        });
    }
}
