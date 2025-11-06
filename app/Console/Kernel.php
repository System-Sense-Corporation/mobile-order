<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    // VVVVV พี่โดนัทจะ "เพิ่ม" (Add) ... "คำสั่งใหม่" (New Command) ... ของเรา...
    // VVVVV ...ไว้ใน "ตาราง" (Array) ... นี้ค่ะ!
    protected $commands = [
        // ... (ที่นี่... อาจจะมี 'คำสั่ง' (Commands) ... 'อันเก่า' (Old) ... ของหนิงอยู่...
        // ...ไม่ต้องไปลบมันน้า!)

        // VVVV "เพิ่ม" (Add) ... บรรทัดนี้... "ต่อท้าย" (At the end) ... เลยค่ะ! VVVV
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}