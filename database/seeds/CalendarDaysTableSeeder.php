<?php

use Illuminate\Database\Seeder;
use App\CalendarDay;

class CalendarDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CalendarDay::class, 50)->create();
    }
}
