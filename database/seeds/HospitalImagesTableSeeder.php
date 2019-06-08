<?php

use Illuminate\Database\Seeder;
use App\HospitalImage;
use App\Hospital;
use Illuminate\Support\Facades\File;
use Faker\Factory;

class HospitalImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $filepath = storage_path('/app/public/images/hospitals');
        if (File::exists($filepath)) {
            File::deleteDirectory($filepath);
        }

        File::makeDirectory($filepath, 0755, true);

        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            $image = $faker->image($filepath, 300, 300, null, true);
            $path_info = pathinfo($image);
            factory(HospitalImage::class)->create([
                'hospital_id'=> $hospital->id,
                'name' => $path_info['filename'],
                'extension' => $path_info['extension'],
                'path' => '/images/hospitals/'.$path_info['filename'].'.jpg'
            ]);
        }
    }
}
