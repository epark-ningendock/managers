<?php

use Illuminate\Database\Seeder;
use App\EmailTemplate;

class EmailTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(EmailTemplate::class, 10)->create();
    }
}
