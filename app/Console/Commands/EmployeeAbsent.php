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
        $users = User::whereHas('role', function($q){$q->whereIn('name', ['Employee']);})->get();
        $carbon = Carbon::today();
        $attendance  = Carbon::parse($carbon)->timestamp;
        foreach ($users as $user)
        {
            $check_attendance = \App\Attendance::whereBetween('check_in_time',[$attendance ,$carbon->endOfDay()->timestamp])->where('user_id',$user->id)->first();
            if($check_attendance == null){
                $inform = \App\Inform::whereBetween('attendance_date',[$attendance, $carbon->endOfDay()->timestamp])->where('user_id',$user->id)->first();

                if(!($inform == null)){

                    $user->attendance()->create(['check_in_time'=>Carbon::today()->addHours(23)->addMinute(59)->timestamp,'check_out_time'=>Carbon::today()->addHours(23)->addMinute(59)->timestamp,'attendance_type'=>'LeaveBySystem','leave_id'=>$inform->id,'leave_comment'=>$inform->reason,'informed'=>true,'late_informed'=>$inform->inform_late]);
                }
                else{
                    $user->attendance()->create(['check_in_time'=>Carbon::today()->addHours(23)->addMinute(59)->timestamp,'check_out_time'=>Carbon::today()->addHours(23)->addMinute(59)->timestamp,'attendance_type'=>'AbsentBySystem',]);
                }
            }
        }
    }
}
