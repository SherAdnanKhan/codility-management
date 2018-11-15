<?php

namespace App\Console\Commands;

use App\Mail\EmployeeLessTimeConsumed;
use App\Mail\MailCheckIn;
use App\Mail\MailLateEmployee;
use App\Mail\WeeklyReport;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendLateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'late:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command is used for mailing a report of previous week ';

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
        $today  = $carbon->startOfDay()->timestamp;
        $late_users=array();
        $late_users_data=array();


//        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->where('abended',false)->get();

                $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->where('abended',false)->get();

        foreach ($users as $user)
        {
            if($user->checkInTime){
                $get_user_time=Carbon::parse($user->checkInTime)->format('H:i');
                $user_date=Carbon::parse($get_user_time)->timestamp;
                $half_past_hour =Carbon::now()->subMinutes(45)->format('H:i');
                $now_time=Carbon::now()->format('H:i');
                    if ($get_user_time > $half_past_hour){
                        if($get_user_time < $now_time){


            $check_attendance = $user->attendance()->whereBetween('check_in_time', [$today, Carbon::now()->timestamp])->where('user_id',$user->id)->first();

            $informs=$user->informs()->whereBetween('attendance_date', [$today, Carbon::now()->timestamp])->first();
            if ($check_attendance== null && $informs == null)
            {
                $late_users[] = $user->name;
                $late_users_data[]=$user->email;
            }
                        }
                    }
            }
        }
        if (!(empty($late_users) && empty($late_users_data))) {
            Mail::send(new MailLateEmployee($late_users_data));
            Mail::send(new MailCheckIn($late_users));
        }
    }
}