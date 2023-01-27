<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // update the disposable domains list
        $schedule->command('disposable:update')->weekly();

        // update geoip database
        $schedule->command('geoip:update')->weekly();
        $schedule->command('geoip:clear')->weekly();

        // uptime and ssl monitor
        $schedule->command('monitor:check-uptime')->everyMinute();
        $schedule->command('monitor:check-certificate')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
