<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('app:send-broadcast-winner')->everyMinute();
        // $schedule->command('app:send-broadcast-winner')->dailyAt('20:30');
        $schedule->command('app:send-broadcast-winner')->cron('* * * * *');
        $schedule->command('app:notify-cron-job')->cron('* * * * *');
    }

    /**
     * Register the commands for the application.
     */
    protected $commands = [
        \App\Console\Commands\NotifyCronJob::class,
        \App\Console\Commands\SendBroadcastWinner::class,
    ];
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
