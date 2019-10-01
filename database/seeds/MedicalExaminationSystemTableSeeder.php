<?php

use App\MedicalExaminationSystem;
use Illuminate\Database\Seeder;

class MedicalExaminationSystemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MedicalExaminationSystem::class, 50)->create();
    }
}
