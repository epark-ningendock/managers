<?php

use Illuminate\Database\Seeder;

class RailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Rail::class, 50)->create();
    }
}
