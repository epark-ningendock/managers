<?php

use App\Hospital;
use App\MedicalTreatmentTime;
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

        factory(Hospital::class, 50)->create()->each(function ($hospital) {
            factory(MedicalTreatmentTime::class)->create(['hospital_id' => $hospital->id]);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Hospital::reguard();
    }
}
