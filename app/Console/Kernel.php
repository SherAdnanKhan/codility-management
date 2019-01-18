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
        'App\Console\Commands\SendTask',
        'App\Console\Commands\SendReport',
        'App\Console\Commands\FridayReport',
        'App\Console\Commands\YearlyReport',
        'App\Console\Commands\SendLateReport'

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
//        $schedule->command('mail:task')->cron('*/60 * * * 1-6');
//        $schedule->command('late:report')->everyFifteenMinutes()->weekdays();
//        $schedule->command('employee:absent')->cron('55 23 * * 1-5');
//        $schedule->command('mail:report')->cron('0 8 * * 2-6');
//        $schedule->command('friday:report')->saturdays()->at('9:00');
//        $schedule->command('yearly:report')->at('23:59')->when(function () {
//            return \Carbon\Carbon::now()->endOfMonth()->isToday();
//        });
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
