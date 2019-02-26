<?php

namespace App\Console\Commands;

use App\Helper\Helper;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ManageCompensatory extends Command
{
    public $public_holiday;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manage:compensatory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command have calculate all leaves and manage the compensatory leaves';

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
        $start_of_year=Carbon::now()->startOfYear();
        $end_of_year=Carbon::now()->endOfYear();
        $get_user = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();
        foreach ($get_user as $user) {
            $total_absent = 0;
            $user_attendance = $user->attendance()->whereBetween('check_in_time', [$start_of_year->timestamp, $end_of_year->timestamp])->get();
            $this->public_holiday=0;
            if ($user_attendance != null) {

                foreach ($user_attendance as $attendance) {

                    if((Helper::check_leaveby_admin($attendance->getOriginal('attendance_type')) == true ) || ( Helper::check_informed_leave($attendance->getOriginal('attendance_type')) == true )){
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

            $update_leaves=$user->update(['count_use_leaves'=> $total_absent - $this->public_holiday]);
        }
    }
}
