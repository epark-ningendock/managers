<?php

use App\ContractInformation;
use Illuminate\Database\Seeder;
use App\HospitalStaff;

class ContractInformationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospital_staffs = HospitalStaff::all();
        foreach ($hospital_staffs as $hospital_staff) {
            factory(ContractInformation::class)->create([
                'hospital_staff_id' => $hospital_staff->id
            ]);
        }
    }
}
