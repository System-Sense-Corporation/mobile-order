<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Keep empty to avoid any duplicate command registration.
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Do NOT load any console commands or route-based console bindings.
     */
    protected function commands(): void
    {
        // Intentionally disabled to prevent loading any commands:
        // $this->load(__DIR__.'/Commands');

        // Also disable route-based console commands:
        // if (file_exists(base_path('routes/console.php'))) {
        //     require base_path('routes/console.php');
        // }
    }
}
