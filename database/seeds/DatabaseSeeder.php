<?php

use App\BillingMailHistory;
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
            // ClassificationTypeSeeder::class, CSVからインポート
            DepartmentsTableSeeder::class,
            StaffsTableSeeder::class,
            // MinorClassificationsTableSeeder::class, CSVからインポート
            HospitalTableSeeder::class,
            HospitalStaffsTableSeeder::class,
            ContractPlansTableSeeder::class,
            FeeRateSeeder::class,
            ContractInformationTableSeeder::class,
            HospitalImagesTableSeeder::class,
            OptionsTableSeeder::class,
            TaxClassesTableSeeder::class,
            ImageOrdersTableSeeder::class,
            CalendarsTableSeeder::class,
            CalendarDaysTableSeeder::class,
            CoursesTableSeeder::class,
            EmailTemplatesTableSeeder::class,
            CustomersSeeder::class,
            BillingTableSeeder::class,
            BillingMailHistory::class,
            // StationsTableSeeder::class, CSVからインポート
            // RailsTableSeeder::class, CSVからインポート
            MedicalExaminationSystemTableSeeder::class,
            // PrefecturesSeeder::class, CSVからインポート
            // DistrictCodeSeeder::class, CSVからインポート
        ]);
    }
}
