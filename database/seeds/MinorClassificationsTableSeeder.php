<?php

use Illuminate\Database\Seeder;
use App\MinorClassification;

class MinorClassificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MinorClassification::class, 'with_major_middle', 50)->create();
    }
}
