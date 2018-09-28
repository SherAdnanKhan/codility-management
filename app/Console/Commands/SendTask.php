<?php

namespace App\Console\Commands;

use App\Attendance;
use App\Leave;
use App\Mail\MailTask;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail Whose CheckOut in Last Hour';

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
        $carbon = Carbon::now();
        $today = $carbon->subHour(1)->timestamp;
        $get_task[] = array();
        $users = User::whereHas('attendance', function ($q) {
            $q->whereBetween('check_out_time',[Carbon::now()->subHour(1)->timestamp, Carbon::now()->timestamp]);
        })->whereDoesntHave('tasks', function ($q) {
            $q->whereBetween('date',[Carbon::now()->startOfDay()->timestamp, Carbon::now()->endOfDay()->timestamp]);
        })->get();
        $check_attendance = Attendance::whereBetween('check_out_time', [Carbon::now()->subHour(1)->timestamp, Carbon::now()->timestamp])->orderBy('user_id','asc')->get();
//        foreach ($check_attendance as $attendance) {
//            $get_task[] = Task::whereBetween('date', [Carbon::now()->startOfDay()->timestamp, Carbon::now()->timestamp])->where('user_id', $attendance->user_id)->get();
//        }
        if ($check_attendance->isNotEmpty()) {
            Mail::send(new MailTask($check_attendance, $users));
        }
    }
}
