<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        'App\Console\Commands\DatabaseBackUp',
        Commands\ResetAdminPassword::class,
    ];
 

    protected function schedule(Schedule $schedule)
    {

        // $schedule->command('tenders:send-notifications')->everyMinute();
        // $schedule->command('futureclient:send-notifications')->everyMinute();
        // $schedule->command('clientrespond:send-notifications')->everyMinute();
        // $schedule->command('futureclientrespond:send-notifications')->everyMinute();
        // $schedule->command('tenderrespond:send-notifications')->everyMinute();

        $schedule->command('futureclient:send-notifications')->dailyAt('08:00');
        $schedule->command('tenders:send-notifications')->dailyAt('08:00');
        $schedule->command('clientrespond:send-notifications')->dailyAt('08:00');
        $schedule->command('futureclientrespond:send-notifications')->dailyAt('08:00');
        $schedule->command('tenderrespond:send-notifications')->dailyAt('08:00');

        // $schedule->command('database:backup')->dailyAt('23:00');
        
        // $schedule->command('tenders:send-reminders')->daily(); 
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
