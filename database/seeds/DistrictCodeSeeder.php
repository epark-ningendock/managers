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
        // 47都道府県分
        for ($i = 1; $i <= 47; $i++) {
            factory(DistrictCode::class)->create([
                'prefecture_id' => $i
            ]);
        }
    }
}
