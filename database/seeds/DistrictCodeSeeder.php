<?php

use App\DistrictCode;
use Illuminate\Database\Seeder;

class DistrictCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DistrictCode::class, 'with_major_class_id', 50)->create();
    }
}
