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
    protected $commands = [
        //Employee Mark Absent OR Leave
        'App\Console\Commands\EmployeeAbsent',
        'App\Console\Commands\LateEmployee',
        'App\Console\Commands\SendTask',
        'App\Console\Commands\SendReport'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('mail:task')
            ->hourly();
        $schedule->command('late:employee')
            ->hourly();
        $schedule->command('employee:absent')
            ->dailyAt('23:30');
        $schedule->command('mail:report')
            ->dailyAt('23:50');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
