<?php

use Illuminate\Database\Seeder;
use App\Hospital;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\HospitalDetail;

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
            $hospital_major_classification = factory(HospitalMajorClassification::class, 1)->create()->first();
            $hospital_middle_classification = factory(HospitalMiddleClassification::class, 1)->create(['major_classification_id' => $hospital_major_classification->id])->first();
            $hospital_minor_classification = factory(HospitalMinorClassification::class, 1)->create(['middle_classification_id' => $hospital_middle_classification->id])->first();
            factory(HospitalDetail::class, 50)->create([
                'hospital_id' => $hospital->id,
                'minor_classification_id' => $hospital_minor_classification->id,
            ]);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Hospital::reguard();
    }
}
