<?php

namespace App\Console\Commands;

use App\Inform;
use App\Leave;
use App\Mail\EmployeeLessTimeConsumed;
use App\Mail\MailCheckIn;
use App\Mail\MailLateEmployee;
use App\Mail\WeeklyReport;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        $late_user_uninforms=array();


//        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->where('abended',false)->get();

        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->where('abended',false)->get();
        foreach ($users as $user)
        {

            if($user->checkInTime){
                $get_user_time=Carbon::parse($user->checkInTime)->format('H:i');
                $user_date=Carbon::parse($get_user_time)->timestamp;
                $half_past_hour =Carbon::now()->subMinutes(16)->format('H:i');
                $now_time=Carbon::now()->format('H:i');
                if ($get_user_time > $half_past_hour){
                    if($get_user_time < $now_time){
                        $check_attendance = $user->attendance()->whereBetween('check_in_time', [$today, Carbon::now()->timestamp])->where('user_id',$user->id)->first();
                        $informs=$user->informs()->whereBetween('attendance_date', [$today, Carbon::now()->timestamp])->first();
                        //start Update if employee have an approved leave
                        if ($informs == null) {
                            $request_leave = DB::table('request_leaves')->whereRaw('"' . Carbon::now()->startOfDay()->timestamp . '" between `from_date` and `to_date`')->where('user_id', $user->id)->where('approved', '<>', '2')->first();
                            if ($request_leave != null) {

                            $request_from_date = Carbon::createFromTimestamp($request_leave->from_date);
                            $request_to_date = Carbon::createFromTimestamp($request_leave->to_date);
                            if ($user->workingDays == 5) {
                                $get_leave_dates = $user->generateDateRange($request_from_date, $request_to_date);
                            } elseif ($user->workingDays == 6) {
                                $get_leave_dates = $user->generateDateRangeWithSunday($request_from_date, $request_to_date);
                            }
                            $today_date = Carbon::now()->toDateString();
                            $check_exist_in_leave = in_array($today_date, $get_leave_dates);
                            if ($check_exist_in_leave == true) {
                                $informs = Inform::create([
                                    'attendance_date' => Carbon::now()->startOfDay()->timestamp,
                                    'inform_at' => Carbon::parse($request_leave->created_at)->timestamp,
                                    'user_id' => $user->id,
                                    'inform_type' => 'leave',
                                    'reason' => $request_leave->reason,
                                    'inform_late' => false,
                                    'leave_type' => $request_leave->leave_id == null ? Leave::first()->id : $request_leave->leave_id,
                                    'request_id' => $request_leave->id
                                ]);
                            }
                        }
                        }
                        //end Update if employee have an approved leave


            if ($check_attendance == null && $informs == null)
            {
                $late_users_data[]=array('email'=>$user->email,'name'=>$user->name);
                $late_user_uninforms[]=$user->name;
            }
            if ($check_attendance ==  null && $informs != null){
                $late_users[] = $user->name;
            }
                        }
                    }
            }
        }

//if have informs
        if ((!(empty($late_users))) && empty($late_user_uninforms)) {

            Mail::send(new MailCheckIn($late_users,$late_user_uninforms));
        }
//if have uninforms
        if ((!(empty($late_user_uninforms))) && empty($late_users)) {



            Mail::send(new MailCheckIn($late_users,$late_user_uninforms));

        }
//if have both
        if ((!(empty($late_user_uninforms)) && (!empty($late_users)))) {

            Mail::send(new MailCheckIn($late_users,$late_user_uninforms));


        }

        if (!(empty($late_users) && empty($late_users_data))) {
            foreach ($late_users_data as $late_users_detail) {
                Mail::send(new MailLateEmployee($late_users_detail));

            }
        }

    }
}
