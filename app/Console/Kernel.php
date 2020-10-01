<?php

namespace App\Console;

use App\Console\Commands\CourseCloseCheckCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\PvAggregateCommand::class,
        Commands\TemporaryReservationCheckCommand::class,
        Commands\ClaimRecordCreateCommand::class,
        Commands\CourseCloseCheckCommand::class,
        Commands\CalendarDaysCreateCommand::class,
				Commands\CalendarDaysCreate2009Command::class,
        /** 路線情報更新バッチ */
        Commands\EkiSpertManagerCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('pv-aggregate')->dailyAt('02:00');
        $schedule->command('temporary-reservation-check')->dailyAt('06:30');
        $schedule->command('claim-record-create')->monthlyOn(21, '05:00');
        $schedule->command('course-close-check')->monthlyOn(1, '05:15');
        $schedule->command('course-close-check')->monthlyOn(15, '05:15');
        $schedule->command('calendar-day-create')->monthlyOn(20, '02:05');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
