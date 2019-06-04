<?php

use App\Hospital;
use Illuminate\Database\Seeder;

class HospitalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hospital::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        factory(Hospital::class, 50)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Hospital::reguard();
    }
}
