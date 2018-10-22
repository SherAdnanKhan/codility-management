<?php

namespace App\Console\Commands;

use App\Attendance;
use App\Mail\MailReport;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail a Report to Admin of Employee Whole Day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start_date = Carbon::yesterday()->addHours(9)->timestamp;
        $end_date = Carbon::now()->timestamp;
        $get_attendance = Attendance::whereBetween('check_out_time', [$start_date, $end_date])->orderBy('user_id','asc')->get();
        $tasks = Task::whereBetween('date',[Carbon::yesterday()->timestamp, Carbon::now()->timestamp])->orderBy('user_id','asc')->get();
        $users = User::whereHas('attendance', function ($q) {
            $q->whereBetween('check_out_time',[Carbon::yesterday()->addHours(9)->timestamp, Carbon::now()->timestamp]);
        })->whereDoesntHave('tasks', function ($q) {
            $q->whereBetween('date',[Carbon::yesterday()->addHours(9)->timestamp, Carbon::now()->endOfDay()->timestamp]);
        })->get();
        if ($get_attendance->isNotEmpty()) {
            Mail::send(new MailReport($get_attendance, $users, $tasks));
        }
        }
}
