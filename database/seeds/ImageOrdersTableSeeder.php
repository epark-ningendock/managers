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

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_FACILITY_MAIN,
            'image_location_number' => 1,
            'name' => '施設情報　施設メイン（PC用）',
            'order' => 1,
        ]);

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_FACILITY_MAIN,
            'image_location_number' => 2,
            'name' => '施設情報　施設メイン（SP用）旧システム移行用　新は使わない',
            'order' => 2,
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ImageOrder::create([
                'image_group_number' => ImageOrder::IMAGE_GROUP_FACILITY_SUB,
                'image_location_number' => $i,
                'name' => '施設情報　－　サブメイン'. $i,
                'order' => $i,
            ]);
        }

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_TOP,
            'image_location_number' => 1,
            'name' => '施設情報　－　TOP (使わない)',
            'order' => 1,
        ]);

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_MAP,
            'image_location_number' => 1,
            'name' => '施設情報　－　地図・アクセス',
            'order' => 1,
        ]);

        for ($i = 1; $i <= 4; $i++) {
            ImageOrder::create([
                'image_group_number' => ImageOrder::IMAGE_GROUP_SPECIALITY,
                'image_location_number' => $i,
                'name' => '施設情報　－　こだわりその'. $i,
                'order' => $i,
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            ImageOrder::create([
                'image_group_number' => ImageOrder::IMAGE_GROUP_STAFF,
                'image_location_number' => $i,
                'name' => '施設情報　－　医師・スタッフ'. $i,
                'order' => $i,
            ]);
        }

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_ANOTHER,
            'image_location_number' => 1,
            'name' => '施設写真　－　スタッフ１',
            'order' => 1,
        ]);

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_ANOTHER,
            'image_location_number' => 2,
            'name' => '施設写真　－　設備１',
            'order' => 2,
        ]);

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_ANOTHER,
            'image_location_number' => 3,
            'name' => '施設写真　－　院内１',
            'order' => 3,
        ]);

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_ANOTHER,
            'image_location_number' => 4,
            'name' => '施設写真　－　外観１',
            'order' => 4,
        ]);

        ImageOrder::create([
            'image_group_number' => ImageOrder::IMAGE_GROUP_ANOTHER,
            'image_location_number' => 5,
            'name' => '施設写真　－　その他１',
            'order' => 5,
        ]);
    }
}

