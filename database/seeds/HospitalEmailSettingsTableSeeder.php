<?php

use Illuminate\Database\Seeder;
use App\HospitalEmailSetting;

class HospitalEmailSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(HospitalEmailSetting::class, 10)->create();
    }
}
