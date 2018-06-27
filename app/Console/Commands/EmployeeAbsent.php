<?php

namespace App\Console\Commands;

use App\Leave;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class EmployeeAbsent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'If Employee not MarkAttendance on Whole Day,Mark Absent or Leave of Employee  ';

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
        $users = User::all();
        $carbon = Carbon::today();
        $attendance  = Carbon::parse($carbon)->timestamp;
        $limit = Carbon::parse($carbon);
        $add_day = $limit->addDays(1);
        $limit_date = strtotime($add_day);
        foreach ($users as $user)
        {
            $check_attendance = \App\Attendance::whereBetween('check_in_time',[$attendance ,$limit_date])->where('user_id',$user->id)->first();
            if($check_attendance == null){
                $inform = \App\Inform::whereBetween('attendance_date',[$attendance, $limit_date])->where('user_id',$user->id)->first();

                if(!($inform == null)){

                    $user->attendance()->create(['check_in_time'=>$attendance,'check_out_time'=>$attendance,'attendance_type'=>'LeaveBySystem','leave_id'=>$inform->id,'leave_comment'=>$inform->reason,'informed'=>true,'late_informed'=>$inform->inform_late]);
                }
                else{
                    $user->attendance()->create(['check_in_time'=>$attendance,'check_out_time'=>$attendance,'attendance_type'=>'AbsendBySystem',]);
                }
            }
        }
        $leave =Leave::create(['name'=>'cron','color_code'=>'#fff','allowed'=>'2']);
    }
}
