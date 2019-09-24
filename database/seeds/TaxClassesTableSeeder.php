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
        factory(TaxClass::class, 100)->create();
    }
}
