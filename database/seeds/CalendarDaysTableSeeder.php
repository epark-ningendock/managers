<?php

use Illuminate\Database\Seeder;
use App\CalendarDay;
use App\Calendar;

class CalendarDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $calendars = Calendar::all();
        foreach($calendars as $calendar) {
            factory(CalendarDay::class, 10)->create([
                'calendar_id' => $calendar->id
            ]);
        }

    }
}
