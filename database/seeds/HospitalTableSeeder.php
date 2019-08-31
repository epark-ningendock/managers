<?php

use App\Hospital;
use App\MedicalTreatmentTime;
use Illuminate\Database\Seeder;
use App\HospitalMajorClassification;
use App\HospitalMiddleClassification;
use App\HospitalMinorClassification;
use App\HospitalDetail;
use App\HospitalEmailSetting;

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

        $minors = HospitalMinorClassification::all()->toArray();

        factory(Hospital::class, 50)->create()->each(function ($hospital) use ($minors) {
            factory(HospitalEmailSetting::class)->create(['hospital_id' => $hospital->id]);
            factory(MedicalTreatmentTime::class)->create(['hospital_id' => $hospital->id]);
            foreach ($minors as $minor) {
                factory(HospitalDetail::class)->create([
                    'hospital_id' => $hospital->id,
                    'minor_classification_id' => $minor['id'],
                ]);
            }
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Hospital::reguard();
    }
}
