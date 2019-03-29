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
        DB::table('staffs')->insert([
            'name' => 'epark-dev',
            'email' => 'epark-dev@example.com',
            'login_id' => 'epark-dev',
            'password' => bcrypt('PassW@rd01'),
            'authority' => '1'
        ]);
    }
}
