<?php

namespace App\Console;

use App\Console\Commands\DeleteExpiredWarning;
use App\Console\Commands\GenerateI18NTranslation;
use App\Console\Commands\SchedulerFbNoti;
use App\Console\Commands\SttTest;
use App\Console\Commands\SttVov;
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
        GenerateI18NTranslation::class,
        SchedulerFbNoti::class,
        SttVov::class,
        DeleteExpiredWarning::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         #$schedule->command('gg-cloud:stt-vov')->withoutOverlapping();
         $schedule->command('fbnoti-scheduler:run')->withoutOverlapping();
         $schedule->command('warning:delete-expired')->everyFifteenMinutes();
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
