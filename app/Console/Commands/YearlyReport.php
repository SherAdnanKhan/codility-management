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
    public $get_employement_month;
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

                }
            }
            $collection->put('total_absent',$total_absent+$user->abended);
            $user_current_month_attendance=$user->attendance()->whereBetween('check_in_time',[Carbon::now()->startOfMonth()->timestamp,Carbon::now()->endOfMonth()->timestamp])->orderBy('id','desc')->get();

            if ($user_current_month_attendance != null){
                foreach ($user_current_month_attendance as $current_month_attendance) {

                    if ((Helper::check_leaveby_admin($current_month_attendance->getOriginal('attendance_type')) == true) || (Helper::check_informed_leave($attendance->getOriginal('attendance_type')) == true )) {
                        $current_month_absent += 1;

                    }
                    if (Helper::check_uninformed_leave($current_month_attendance->getOriginal('attendance_type')) == true) {
                        $current_month_absent += 1;
                    }
                }
            }
            $collection->put('current_month_absent',$current_month_absent);
            $now_month=Carbon::now()->month;
            if(!((Carbon::parse($user->joiningDate)->month == $now_month) && (Carbon::parse($user->joiningDate)->year == Carbon::now()->year))) {
                $this->get_employement_month = (Carbon::parse($user->joiningDate)->month - $now_month);
            }
            if(Carbon::parse($user->joiningDate)->month == $now_month && Carbon::parse($user->joiningDate)->year == Carbon::now()->year) {
                $this->get_employement_month =1;
            }
            $allowed_absent=abs($this->get_employement_month) * Helper::leave_cotta() ;
            $collection->put('allowed_absent',$allowed_absent);
            $user_details[]=array($collection->all());

        }
        if (!(empty($user_details))){
            Mail::send(new Year($user_details));
        }


    }
}
