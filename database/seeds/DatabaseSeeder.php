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
            MinorClassificationsTableSeeder::class,
            HospitalTableSeeder::class,
            HospitalStaffsTableSeeder::class,
            FeeRateSeeder::class,
            ContractInformationTableSeeder::class, // comment off if you need it
            HospitalImagesTableSeeder::class,
            OptionsTableSeeder::class,
            TaxClassesTableSeeder::class,
            ImageOrdersTableSeeder::class,
            CalendarsTableSeeder::class,
	        CalendarDaysTableSeeder::class,
            CoursesTableSeeder::class,
            EmailTemplatesTableSeeder::class,
            CustomersSeeder::class,
            PrefecturesSeeder::class,
            DistrictCodeSeeder::class,
        ]);
    }
}
