<?php

namespace App\Console\Commands;

use App\Mail\EmployeeLessTimeConsumed;
use App\Mail\EmployeeLessTimeConsumedReport;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class FridayReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'friday:report';

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
        $emails=array();
        $names=array();
        $start_week=Carbon::now()->startOfWeek()->timestamp;
        $end_week=Carbon::now()->endOfWeek()->subDays(1)->subHours(16)->timestamp;
        //Get All Employee
        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->get();
        foreach ($users as $user_attendance){
            $total_minutes = 0;
            $check_attendance=$user_attendance->attendance()->whereBetween('check_in_time',[$start_week,$end_week])->first();
            $get_attendance=$user_attendance->attendance()->whereBetween('check_in_time',[$start_week,$end_week])->get();
            //Check employee marked attendance at least one in a  whole week
            if ($check_attendance != null){
                foreach ($get_attendance as $attendance){
                    //check if employee have check in time in attendance
                    if ($attendance->check_in_time )
                    {
                        //check employee if have schedule task during their attendance check in and checkout time
                        if($attendance->task_friday(Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,Carbon::parse($attendance->check_out_time)->timestamp)->first() != null)
                        {
                            $get_task=$attendance->task_friday(Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->get();

                            foreach ($get_task as $task){
                                // check if employee task have time
                                if ($task->time_take) {
                                    $explode = explode(':', $task->time_take);
                                    $total_minutes +=($explode[0]*60) + ($explode[1])  ;

                                }
                            }
                        }
                    }
                }
                $default_check_in_time  = Carbon::parse($user_attendance->checkInTime);
                $default_check_out_time = Carbon::parse($user_attendance->checkOutTime);
                $break_time= Carbon::createFromTimestamp($user_attendance->breakAllowed)->format("h:i");
                $explode_break_time = explode(':', $break_time);
                $total_break_minutes=($explode_break_time[0]*60) + ($explode_break_time[1]);
                $subtract_time = $default_check_out_time->diffInRealMinutes($default_check_in_time) - $total_break_minutes ;
                $total_day_time =$subtract_time * 5;
                $division = $total_day_time/100;
                $mulitpication= $division * 10;
                $compensate = $total_day_time - $mulitpication;
                $lessTime= +($compensate - $total_minutes);
                if ($total_minutes <= $compensate){

                    $loggedTime=sprintf("%02d:%02d", floor($total_minutes/60), $total_minutes%60);
                    $requiredTime=sprintf("%02d:%02d", floor($compensate/60), $compensate%60);
                    $lessHours=sprintf("%02d:%02d", floor($lessTime/60), $lessTime%60);
                    $names []=array('name'=>$user_attendance->name,'loggedTime'=>$loggedTime,'requiredTime'=>$requiredTime,'lessHours'=>$lessHours);
                    $emails[]=$user_attendance->email;
                }

            }
            //Check employee if not marked attendance for whole week get thier name and email
            elseif ($check_attendance == null){
                $default_check_in_time  = Carbon::parse($user_attendance->checkInTime);
                $default_check_out_time = Carbon::parse($user_attendance->checkOutTime);
                $break_time= Carbon::createFromTimestamp($user_attendance->breakAllowed)->format("h:i");
                $explode_break_time = explode(':', $break_time);
                $total_break_minutes=($explode_break_time[0]*60) + ($explode_break_time[1]);
                $subtract_time = $default_check_out_time->diffInRealMinutes($default_check_in_time) - $total_break_minutes ;
                $total_day_time =$subtract_time * 5;
                $division = $total_day_time/100;
                $mulitpication= $division * 10;
                $compensate = $total_day_time - $mulitpication;
                $lessTime= +($compensate - $total_minutes);
                $loggedTime=sprintf("%02d:%02d", floor($total_minutes/60), $total_minutes%60);
                $requiredTime=sprintf("%02d:%02d", floor($compensate/60), $compensate%60);
                $lessHours=sprintf("%02d:%02d", floor($lessTime/60), $lessTime%60);
                $names []=array('name'=>$user_attendance->name,'loggedTime'=>$loggedTime,'requiredTime'=>$requiredTime,'lessHours'=>$lessHours);
                $emails[]=$user_attendance->email;

            }
        }
        if (!(empty($emails) && empty($names))){
            Mail::send(new EmployeeLessTimeConsumedReport($names));
            Mail::send(new EmployeeLessTimeConsumed($emails));
        }

    }
}
