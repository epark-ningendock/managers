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
            ClassificationTypeSeeder::class,
            DepartmentsTableSeeder::class,
            StaffsTableSeeder::class,
            HospitalStaffsTableSeeder::class,
            DistrictCodeSeeder::class,
            ContractInformationTableSeeder::class, // comment off if you need it
            HospitalTableSeeder::class,
            HospitalImagesTableSeeder::class,
            OptionsTableSeeder::class,
            TaxClassesTableSeeder::class,
            ImageOrdersTableSeeder::class,
            CalendarsTableSeeder::class,
            CoursesTableSeeder::class,
            EmailTemplatesTableSeeder::class,
            ReceprionEmailSettingsTableSeeder::class,
            CustomersSeeder::class,
            PrefecturesSeeder::class
        ]);
    }
}
