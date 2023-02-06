<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\YearlyReport as Year;
class YearlyReport extends Command
{
    public $current_public_holiday;
    public $get_employement_month;
    public $public_holiday;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yearly:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Yearly Report form the start on employee month to end of current month';

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
        $get_user=User::whereHas('role',function ($q){$q->whereIn('name',['Employee']);})->where('abended',false)->get();


        foreach($get_user as $user){
            $user_joining_date=Carbon::parse($user->joiningDate)->year;
            $current_year=Carbon::now()->year;
            if ($user_joining_date == $current_year) {
                $start_office = Carbon::parse($user->joiningDate)->startOfDay();
            }else{
                $start_office = Carbon::now()->startOfYear();
            }
            $total_absent=0;
            $current_month_absent=0;
            $this->current_public_holiday=0;
            $this->public_holiday=0;
            $final_total_leaves=0;
            $names=array('name'=>$user->name);
            $collection=collect($names);
            $user_attendance=$user->attendance()->whereBetween('check_in_time',[$start_office->timestamp,Carbon::now()->endOfMonth()->timestamp])->orderBy('id','desc')->get();

            if ($user_attendance != null) {
                foreach ($user_attendance as $attendance) {
                    if((Helper::check_leaveby_admin($attendance->getOriginal('attendance_type')) == true ) || ( Helper::check_informed_leave($attendance->getOriginal('attendance_type')) == true )){
//                    if (($attendance->getOriginal('attendance_type') == 'LeaveByAdmin') || ($attendance->getOriginal('attendance_type') == 'LeaveBySystem')) {
                        $total_absent += 1;

                    }
                    if(Helper::check_uninformed_leave($attendance->getOriginal('attendance_type')) == true){
                        $total_absent += 1;
                    }
                    $inform_get =$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp);
                    if ($inform_get != null){
                        if ($attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LEAVE")
                        {
                            $public_holiday_get=$attendance->inform(\Carbon\Carbon::parse($attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($attendance->check_in_time)->endOfDay()->timestamp)->leaves->public_holiday;
                            $this->public_holiday+=$public_holiday_get== true?1:0;

                        }

                    }

                }
            }
            $sub_of_public_holiday=abs($this->public_holiday - $total_absent);
            $collection->put('total_absent',$sub_of_public_holiday);
            $user_current_month_attendance=$user->attendance()->whereBetween('check_in_time',[Carbon::now()->startOfMonth()->timestamp,Carbon::now()->endOfMonth()->timestamp])->orderBy('id','desc')->get();

            if ($user_current_month_attendance != null){
                foreach ($user_current_month_attendance as $current_month_attendance) {

                    if((Helper::check_leaveby_admin($current_month_attendance->getOriginal('attendance_type')) == true ) || ( Helper::check_informed_leave($current_month_attendance->getOriginal('attendance_type')) == true )){
                        $current_month_absent += 1;
//echo "adsf";
                    }
                    if(Helper::check_uninformed_leave($current_month_attendance->getOriginal('attendance_type')) == true){
                        $current_month_absent += 1;
//            echo "123";
                    }
                    $inform_get =$current_month_attendance->inform(\Carbon\Carbon::parse($current_month_attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($current_month_attendance->check_in_time)->endOfDay()->timestamp);
                    if ($inform_get != null){
                        if ($current_month_attendance->inform(\Carbon\Carbon::parse($current_month_attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($current_month_attendance->check_in_time)->endOfDay()->timestamp)->inform_type == "LEAVE")
                        {
                            $public_holiday_gets=$current_month_attendance->inform(\Carbon\Carbon::parse($current_month_attendance->check_in_time)->startOfDay()->timestamp,\Carbon\Carbon::parse($current_month_attendance->check_in_time)->endOfDay()->timestamp)->leaves->public_holiday;
                            $this->current_public_holiday+=$public_holiday_gets== true?1:0;

                        }

                    }
                }
            }
            $sub_of_current_public_holiday=abs($this->current_public_holiday - $current_month_absent);

            $collection->put('current_month_absent',$sub_of_current_public_holiday);
//            $now_month=Carbon::now()->month;
//            if(!((Carbon::parse($user->joiningDate)->month == $now_month) && (Carbon::parse($user->joiningDate)->year == Carbon::now()->year))) {
//                $this->get_employement_month = (Carbon::parse($user->joiningDate)->month - $now_month);
//            }
//            if(Carbon::parse($user->joiningDate)->month == $now_month && Carbon::parse($user->joiningDate)->year == Carbon::now()->year) {
//                $this->get_employement_month =1;
//            }

//            $allowed_absent=abs($this->get_employement_month) * Helper::leave_cotta() ;
            $now_month = Carbon::now()->month;
            if (!((Carbon::parse($user->joiningDate)->month == $now_month) && (Carbon::parse($user->joiningDate)->year == Carbon::now()->year))) {
                $joiningDate=Carbon::parse($user->joiningDate);
                $this->get_employement_month = Carbon::parse($user->joiningDate)->diffInMonths(Carbon::now());

            }
            if (Carbon::parse($user->joiningDate)->month == $now_month && Carbon::parse($user->joiningDate)->year == Carbon::now()->year) {
                $this->get_employement_month = 1;
                echo "asdf";
            }
            //
            //

            if (Carbon::parse($user->joiningDate)->year < Carbon::now()->year) {
                $this->get_employement_month = Carbon::now()->startOfYear()->diffInMonths(Carbon::now()) ;

            }else{
                $this->get_employement_month = 0;

            }
//dd($this->get_employement_month);
            $allowed_absent = abs($this->get_employement_month)+1 ;
            $new=$allowed_absent;
//dd($new*1.5);
//            while ($new >= 1){
//
//                $allowed_absent+=1.5;
//echo "asdf";
//                $new --;
//            }
            $allowed_absent= $new * Helper::leave_cotta();
//var_dump( $allowed_absent);


            if ($user->compensatory_leaves >=1){

                $final_total_leaves=$allowed_absent + $user->compensatory_leaves ;
            }

            $collection->put('allowed_absent',$final_total_leaves != false?$final_total_leaves:$allowed_absent);
            $user_details[]=array($collection->all());

        }
        if (!(empty($user_details))){
            Mail::send(new Year($user_details));
        }


    }
}
