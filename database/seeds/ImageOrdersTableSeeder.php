<?php

use Illuminate\Database\Seeder;
use App\ImageOrder;

class ImageOrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ImageOrder::create([
            'image_group_number' => 0,
            'image_location_number' => 1,
            'name' => '検査コースメイン画像',
            'order' => 1,
        ]);

        ImageOrder::create([
            'image_group_number' => 0,
            'image_location_number' => 2,
            'name' => '受診の流れ(PC用)',
            'order' => 2,
        ]);

        ImageOrder::create([
            'image_group_number' => 0,
            'image_location_number' => 3,
            'name' => '受診の流れ(SP用)',
            'order' => 3,
        ]);
    }
}
