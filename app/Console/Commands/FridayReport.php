<?php

namespace App\Console\Commands;

use App\Helper\Helper;
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
    public $public_holiday;
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
        $date_array = array();
        $current_month = Carbon::now()->month;
        $previous_month = Carbon::now()->subDays(6)->month;
        if ($current_month != $previous_month) {
            $date_collection = collect($date_array);
            $date_collection->put('previous_date_carbon', [Carbon::parse($previous_month . '/1'), Carbon::parse($previous_month . '/1')->endOfMonth()->endOfDay()]);
            $date_collection->put('current_date_carbon', [Carbon::parse($current_month . '/1'), Carbon::now()->subDays(1)->endOfDay()]);

        } else {
            $date_collection = collect($date_array);
            $date_collection->put('current_date_carbon', [Carbon::parse(Carbon::now()), Carbon::now()->subDays(1)->endOfDay()]);
        }

        $users = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();
        foreach ($date_collection as $get_actual_date) {

            $emails = array();
            $names = array();

            //        $start_date_array=array();
            //        $end_date_array=array();
            //        $current_month=Carbon::now()->month;
            //        $previous_month=Carbon::now()->subDays(6)->month;
            //        if($current_month != $previous_month){
            //            $start_date_array[]=Carbon::parse($previous_month . '/1');
            //            $end_date_array[]=Carbon::parse($previous_month . '/1')->endOfMonth()->endOfDay();
            //        }else{
            //            unset($start_date_array);
            //            unset($end_date_array);
            //            $start_date_array[]=Carbon::now();
            //            $end_date_array[]=Carbon::now()->subDays(1)->endOfDay();
            //        }
                        //Get All Employee
            //            $users = User::whereHas('role', function ($q) {
            //                $q->whereIn('name', ['Employee']);
            //            })->where('abended', false)->get();

            foreach ($users as $user_attendance) {
                $total_minutes = 0;
                $sum_lates = 0;
                $sum_leaves = 0;
                $informed_late = 0;
                $absent = 0;
                $names = array('name' => $user_attendance->name);
                $collection = collect($names);
                $sub_of_public_holiday = 0;
                $this->public_holiday = 0;
                $check_attendance = $user_attendance->attendance()->whereBetween('check_in_time', [$get_actual_date[0]->startOfMonth()->timestamp, $get_actual_date[1]->timestamp])->first();
                $get_attendance = $user_attendance->attendance()->whereBetween('check_in_time', [$get_actual_date[0]->startOfMonth()->timestamp, $get_actual_date[1]->timestamp])->orderBy('id', 'desc')->get();
                //Check employee marked attendance at least one in a  whole week
                if ($check_attendance != null) {
                    foreach ($get_attendance as $attendance) {
                        //check if employee have check in time in attendance
                        if ($attendance->check_in_time) {
                            //check if employee have late

                            if (Helper::check_uninformed_late($attendance->getOriginal('attendance_type')) == true) {
                                $sum_lates += 1;
                            }

                            if ((Helper::check_leaveby_admin($attendance->getOriginal('attendance_type')) == true) || (Helper::check_informed_leave($attendance->getOriginal('attendance_type')) == true)) {
                                $sum_leaves += 1;

                            }
                            if (Helper::check_uninformed_leave($attendance->getOriginal('attendance_type')) == true) {
                                $absent += 1;
                            }
                            if (Helper::check_informed_late($attendance->getOriginal('attendance_type')) == true) {

                                if ($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, \Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LATE") {
                                    $informed_late += 1;
                                }
                            }
                            $inform_get = $attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, \Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp);
                            if ($inform_get != null) {
                                if ($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, \Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LEAVE") {
                                    $public_holiday_get = $attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, \Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->leaves->public_holiday;
                                    $this->public_holiday += $public_holiday_get == true ? 1 : 0;
                                }

                            }
                            //check employee if have schedule task during their attendance check in and checkout time
                            if ($attendance->task_friday(Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, Carbon::parse($attendance->check_out_time)->timestamp)->first() != null) {
                                $get_task = $attendance->task_friday(Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp, Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->get();
                                foreach ($get_task as $task) {
                                    // check if employee task have time
                                    if ($task->time_take) {

                                        $explode = explode(':', $task->time_take);
                                        $total_minutes += ($explode[0] * 60) + ($explode[1]);

                                    }
                                }
                            }
                        }
                    }

                    $sub_of_public_holiday = $sum_leaves - $this->public_holiday;

                    $concatinate = $collection->put('late', $sum_lates);
                    $concatinate = $collection->put('leave', $sub_of_public_holiday);
                    $concatinate = $collection->put('informed_late', $informed_late);
                    $concatinate = $collection->put('absent', $absent);
                    $concatinate = $collection->put('email', $user_attendance->email);
                    $concatinate = $collection->put('user_id', $user_attendance->id);

                    $default_check_in_time = Carbon::parse($user_attendance->checkInTime);
                    $default_check_out_time = Carbon::parse($user_attendance->checkOutTime);
                    $break_time = Carbon::createFromTimestamp($user_attendance->breakAllowed)->format("H:i");
                    $explode_break_time = explode(':', $break_time);
                    $total_break_minutes = ($explode_break_time[0] * 60) + ($explode_break_time[1]);
                    $subtract_time = $default_check_out_time->diffInRealMinutes($default_check_in_time) - $total_break_minutes;

                    //edit
//                $get_month_dates = Carbon::now()->endOfMonth()->isSunday();
//                if ($get_month_dates == true){
//                    $this->days=Carbon::now()->endOfMonth()->day - 2;
//                }else{
//                }else{
//                    $this->days=Carbon::now()->day - 1 ;
//
//                }

                    $this->days = $get_actual_date[1]->day;

                    $workdays = array();
                    $type = CAL_GREGORIAN;
                    $month = $get_actual_date[0]->endOfMonth()->month; // Month ID, 1 through to 12.
                    $year = $get_actual_date[0]->endOfMonth()->year; // Year in 4 digit 2018 format.
                    $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
                    $get_date = Carbon::parse($user_attendance->joiningDate)->month;
                    if ($get_date == $month && Carbon::parse($user_attendance->joiningDate)->year == $get_actual_date[0]->year) {
                        $start_date = Carbon::parse($user_attendance->joiningDate)->day;
                    } else {
                        $start_date = 1;
                    }

                    if ($user_attendance->workingDays == 5) {
                        for ($i = $start_date; $i <= $day_count; $i++) {

                            $date = $year . '/' . $month . '/' . $i; //format date
                            $get_name = date('l', strtotime($date)); //get week day
                            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                            //if not a weekend add day to array
                            if ($day_name != 'Sun' && $day_name != 'Sat') {
                                $workdays[] = $i;
                            }

                        }
                    } elseif ($user_attendance->workingDays == 6) {
                        for ($i = $start_date; $i <= $day_count; $i++) {

                            $date = $year . '/' . $month . '/' . $i; //format date
                            $get_name = date('l', strtotime($date)); //get week day
                            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                            //if not a weekend add day to array
                            if ($day_name != 'Sun') {
                                $workdays[] = $i;
                            }

                        }
                    }
                    $collect = collect($workdays);
                    $collect->each(function ($item, $key) {

                        if ($item == $this->days) {
                            $this->total_days_form = $key;
                        }
                    });

                    $subtract_absent_days = $this->total_days_form + 1 - ($absent + $sum_leaves);

                    $total_day_time = $subtract_time * abs($subtract_absent_days);
                    $asdf = $total_day_time;

                    $lessTimeWithoutCompensation = abs($total_day_time - $total_minutes);
                    $division = $total_day_time / 100;
                    $mulitpication = $division * 10;
                    $compensate = $total_day_time - $mulitpication;
                    $getlessTime = +($compensate - $total_minutes);
                    $lessTime = abs($getlessTime);
                    if ($total_minutes <= $compensate && $total_minutes > 0) {
                        $lessTimeWithoutCompensationTime = sprintf("%02d:%02d", floor($lessTimeWithoutCompensation / 60), $lessTimeWithoutCompensation % 60);
                        $loggedTime = sprintf("%02d:%02d", floor($total_minutes / 60), $total_minutes % 60);
                        $requiredWithoutCompansetionTime = sprintf("%02d:%02d", floor($asdf / 60), $asdf % 60);
                        $requiredTime = sprintf("%02d:%02d", floor($compensate / 60), $compensate % 60);
                        $lessHours = sprintf("%02d:%02d", floor($lessTime / 60), $lessTime % 60);
                        $concatinate = $collection->put('loggedTime', $loggedTime);
                        $concatinate = $collection->put('requiredTime', $requiredTime);
                        $concatinate = $collection->put('lessHours', $lessHours);
                        $concatinate = $collection->put('requiredWithoutCompansetionTime', $requiredWithoutCompansetionTime);
                        $concatinate = $collection->put('lessTimeWithoutCompensation', $lessTimeWithoutCompensationTime);
                        $concatinate = $collection->put('user_id', $user_attendance->id);

                        $user_name[] = array($concatinate->all());

                        $names [] = array('name' => $user_attendance->name, 'loggedTime' => $loggedTime, 'requiredTime' => $requiredTime, 'lessHours' => $lessHours);
                        $emails[] = $user_attendance->email;
//                    var_dump($user_name);
                    }

                }

            }
            $start_send_date=$get_actual_date[0];
            $end_send_date=$get_actual_date[1];
            if (!(empty($emails) && empty($user_name))) {
                Mail::send(new WeeklyReport($user_name,$start_send_date,$end_send_date));
                foreach ($user_name as $get_user_detail) {
                    foreach ($get_user_detail as $item) {
//                    Mail::send(new EmployeeLessTimeConsumed($item,$emails));

                    }
                }

            }
        }

    }
}
