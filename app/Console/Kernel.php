<?php

namespace App\Console;

use App\Console\Commands\ClearVoucherCache;
use App\Console\Commands\DoneOrder;
use App\Console\Commands\GenerateI18NTranslation;
use App\Console\Commands\SchedulerFbNoti;
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
        ClearVoucherCache::class,
	    DoneOrder::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
	    $schedule->command('fbnoti-scheduler:run')->withoutOverlapping();
	    $schedule->command('voucher:clear-cache')->daily();
	    $schedule->command('order:finish')->daily();
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
