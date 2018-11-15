<?php

namespace App\Console\Commands;

use App\Mail\EmployeeLessTimeConsumed;
use App\Mail\WeeklyReport;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class FridayReport extends Command
{
    public $total_days_form;
    public $days;
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
        $start_week=Carbon::now()->startOfMonth()->timestamp;
        $end_week=Carbon::now()->timestamp;
        //Get All Employee
        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']); })->where('abended',false)->get();
        foreach ($users as $user_attendance){
            $total_minutes = 0;
            $sum_lates=0;
            $sum_leaves=0;
            $informed_late=0;
            $absent=0;
            $names=array('name'=>$user_attendance->name);
            $collection=collect($names);
            $check_attendance=$user_attendance->attendance()->whereBetween('check_in_time',[$start_week,$end_week])->first();
            $get_attendance=$user_attendance->attendance()->whereBetween('check_in_time',[$start_week,$end_week])->orderBy('id','desc')->get();
            //Check employee marked attendance at least one in a  whole week
            if ($check_attendance != null){
                foreach ($get_attendance as $attendance){
                    //check if employee have check in time in attendance
                    if ($attendance->check_in_time )
                    {
                        //check if employee have late

                        if ($attendance->attendance_type =='UnInformed Late'){
                            $sum_lates+=1;
                        }

                        if(($attendance->attendance_type =='Leave Marked By Admin') || ($attendance->attendance_type =='Informed-Leave Marked By System ')){
                            $sum_leaves+=1;

                        }
                        if($attendance->attendance_type =='Absent Marked By System'){
                            $absent+=1;
                        }
                        if($attendance->attendance_type =='Informed'){
                            if($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LATE"){
                             $informed_late+=1;
                            }
                        }
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
                $concatinate=$collection->put('late',$sum_lates);
                $concatinate=$collection->put('leave',$sum_leaves);
                $concatinate=$collection->put('informed_late',$informed_late);
                $concatinate=$collection->put('absent',$absent);
                $default_check_in_time  = Carbon::parse($user_attendance->checkInTime);
                $default_check_out_time = Carbon::parse($user_attendance->checkOutTime);
                $break_time= Carbon::createFromTimestamp($user_attendance->breakAllowed)->format("h:i");
                $explode_break_time = explode(':', $break_time);
                $total_break_minutes=($explode_break_time[0]*60) + ($explode_break_time[1]);
                $subtract_time = $default_check_out_time->diffInRealMinutes($default_check_in_time) - $total_break_minutes;
                //edit
                $this->days=Carbon::now()->day;
                $workdays = array();
                $type = CAL_GREGORIAN;
                $month = date('n'); // Month ID, 1 through to 12.
                $year = date('Y'); // Year in 4 digit 2018 format.
                $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                for ($i = 1; $i <= $day_count; $i++) {

                    $date = $year.'/'.$month.'/'.$i; //format date
                    $get_name = date('l', strtotime($date)); //get week day
                    $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                    //if not a weekend add day to array
                    if($day_name != 'Sun' && $day_name != 'Sat'){
                        $workdays[] = $i;
                    }

                }
                $collect=collect($workdays);
                $collect->each(function ($item, $key) {
                    if ($item == $this->days) {
                        $this->total_days_form=$key;
                    }
                });
                $total_day_time =$subtract_time * $this->total_days_form;
                $division = $total_day_time/100;
                $mulitpication= $division * 10;
                $compensate = $total_day_time - $mulitpication;
                $getlessTime= +($compensate - $total_minutes);
                $lessTime=abs($getlessTime);
                if ($total_minutes <= $compensate){
                    $loggedTime=sprintf("%02d:%02d", floor($total_minutes/60), $total_minutes%60);
                    $requiredTime=sprintf("%02d:%02d", floor($compensate/60), $compensate%60);
                    $lessHours=sprintf("%02d:%02d", floor($lessTime/60), $lessTime%60);
                    $concatinate=$collection->put('loggedTime',$loggedTime);
                    $concatinate=$collection->put('requiredTime',$requiredTime);
                    $concatinate=$collection->put('lessHours',$lessHours);

                    $user_name[]=array($concatinate->all());

//                    $names []=array('name'=>$user_attendance->name,'loggedTime'=>$loggedTime,'requiredTime'=>$requiredTime,'lessHours'=>$lessHours);
                    $emails[]=$user_attendance->email;
                }

            }
            //Check employee if not marked attendance for whole week get their name and email
            elseif ($check_attendance == null){

                $default_check_in_time  = Carbon::parse($user_attendance->checkInTime);
                $default_check_out_time = Carbon::parse($user_attendance->checkOutTime);
                $break_time= Carbon::createFromTimestamp($user_attendance->breakAllowed)->format("h:i");
                $explode_break_time = explode(':', $break_time);
                $total_break_minutes=($explode_break_time[0]*60) + ($explode_break_time[1]);
                $subtract_time = $default_check_out_time->diffInRealMinutes($default_check_in_time) - $total_break_minutes ;
                $this->days=Carbon::now()->day;
                $workdays = array();
                $type = CAL_GREGORIAN;
                $month = date('n'); // Month ID, 1 through to 12.
                $year = date('Y'); // Year in 4 digit 2018 format.
                $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

            //loop through all days
                for ($i = 1; $i <= $day_count; $i++) {

                    $date = $year.'/'.$month.'/'.$i; //format date
                    $get_name = date('l', strtotime($date)); //get week day
                    $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                    //if not a weekend add day to array
                    if($day_name != 'Sun' && $day_name != 'Sat'){
                        $workdays[] = $i;
                    }

                }
                $collect=collect($workdays);
                $collect->each(function ($item, $key) {
                    if ($item == $this->days) {
                        $this->total_days_form=$key;
                    }
                });
                $total_day_time =$subtract_time * $this->total_days_form;

                $division = $total_day_time/100;
                $mulitpication= $division * 10;
                $compensate = $total_day_time - $mulitpication;
                $lessTime= +($compensate - $total_minutes);
                $loggedTime=sprintf("%02d:%02d", floor($total_minutes/60), $total_minutes%60);
                $requiredTime=sprintf("%02d:%02d", floor($compensate/60), $compensate%60);
                $lessHours=sprintf("%02d:%02d", floor($lessTime/60), $lessTime%60);
                $absent= $this->days;
                $concatinate=$collection->put('absent',$absent);
                $concatinate=$collection->put('loggedTime',$loggedTime);
                $concatinate=$collection->put('requiredTime',$requiredTime);
                $concatinate=$collection->put('lessHours',$lessHours);
                $user_name[]=array($concatinate->all());
                // $names []=array('name'=>$user_attendance->name,'loggedTime'=>$loggedTime,'requiredTime'=>$requiredTime,'lessHours'=>$lessHours);
                $emails[]=$user_attendance->email;

            }
        }

        if (!(empty($emails) && empty($user_name))){
            Mail::send(new WeeklyReport($user_name));
            Mail::send(new EmployeeLessTimeConsumed($emails));
        }

    }
}