<?php

use Illuminate\Database\Seeder;
use App\ReceptionEmailSetting;

class ReceprionEmailSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ReceptionEmailSetting::class, 10)->create();
    }
}
