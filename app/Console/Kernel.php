<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ReduceDaysCommand::class,
        \App\Console\Commands\CollectCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('app:reduce-days-command')->everyMinute();
        $schedule->command('app:collect-command')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
