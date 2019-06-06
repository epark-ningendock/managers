<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StaffsTableSeeder::class,
            HospitalStaffsTableSeeder::class,
            MinorClassificationsTableSeeder::class,
            HospitalTableSeeder::class,
            HospitalImagesTableSeeder::class,
            OptionsTableSeeder::class,
            TaxClassesTableSeeder::class,
            ImageOrdersTableSeeder::class,
            CalendarsTableSeeder::class,
            CoursesTableSeeder::class,
            EmailTemplatesTableSeeder::class,
            ReceprionEmailSettingsTableSeeder::class
        ]);
    }
}
