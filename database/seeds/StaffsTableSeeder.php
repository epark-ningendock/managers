<?php

use Illuminate\Database\Seeder;

class StaffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Staff::class, 50)->make()->each(function($staff, $index) {
            $staff->login_id = "epark-dev-$index";
            $staff->save();
        });
    }
}
