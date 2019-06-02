<?php

use Illuminate\Database\Seeder;
use App\TaxClass;
use Carbon\Carbon;

class TaxClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxClass::create([
            'name' => 'なし',
            'rate' => 0,
            'life_time_from' => Carbon::createFromDate(2014, 4, 1),
            'life_time_to' => Carbon::createFromDate(2037, 12, 31)
        ]);

        TaxClass::create([
            'name' => '消費税８％',
            'rate' => 8,
            'life_time_from' => Carbon::createFromDate(2014, 4, 1),
            'life_time_to' => Carbon::createFromDate(2037, 12, 31)
        ]);
    }
}
