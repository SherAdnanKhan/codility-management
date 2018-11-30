<?php

namespace App\Http\Controllers\Api;

use App\Attendance;
use App\Inform;
use App\TrackerAttendance;
use App\TrackerCalculation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class AttendanceController extends Controller
{
    public function check_in(Request $request)
    {

        $this->validate($request, [
            'check_in_time' => 'required|date',
        ]);
        $check_in_time = Carbon::parse($request->check_in_time)->timestamp;
        $date=Carbon::parse($request->check_in_time)->startOfDay()->timestamp;
        $user = $request->auth;
            $attendances = TrackerAttendance::create([
                'check_in_time' => $check_in_time,
                'user_id'       => $request->auth->id,
                'date'          => $date
            ]);
            if ($attendances)
            {
                return response()->json([
                    'status'        =>'Attendance Marked successFully !',
                    'tracker_id'    =>$attendances->id
                ]);

            }
            else{
                return response()->json([
                    'status'=>'Attendance Marked unsuccessful please try again !'
                ]);

            }
    }
    public function check_out(Request $request)

    {

        $this->validate($request, [
            'check_out_time'    => 'required|date',
            'tracker_id'        => 'required|integer',

            ]);
        $check_out_time = Carbon::parse($request->check_out_time)->startOfDay();

        $get_attendance=$request->auth;
        $get_date=$get_attendance->get_tracker_attendance->where('id',$request->tracker_id)->where('check_out_time',null)->first();

        if ($get_date != null) {

            $convert_date = Carbon::createFromTimestamp($get_date->date)->startOfDay();

            //check if have same day check in and check out
            if ($check_out_time->equalTo($convert_date)) {
                $user = $request->auth;
                $update_attendance = $get_date->update([
                    'check_out_time' => Carbon::parse($request->check_out_time)->timestamp,
                ]);
                if ($update_attendance == true) {
                    //Get check in and out time for getting the difference

                    $get_check_in_time = Carbon::createFromTimestamp($get_date->check_in_time);
                    $get_check_out_time = Carbon::createFromTimestamp($get_date->check_out_time);
                    $get_diff_minutes = $get_check_in_time->diffInRealMinutes($get_check_out_time);
                    $get_calculation=$user->user_tracker_calculation($convert_date->timestamp)->first();
                    if($get_calculation == null){
                        $calculation=TrackerCalculation::create([
                            'time_spent'=>$get_diff_minutes,
                            'user_id'       => $request->auth->id,
                            'date'      =>$convert_date->timestamp
                        ]);

                    }
                    if ($get_calculation != null){
                        $get_time=$get_calculation->time_spent;
                        $calculation=$get_calculation->update([
                            'time_spent' => $get_diff_minutes + $get_time
                        ]);
                    }
                }

            }
            else{
                $user = $request->auth;
                    $get_check_in_time = Carbon::createFromTimestamp($get_date->check_in_time);
                    $get_check_out_time = Carbon::parse($request->check_out_time);
                    $get_diff_minutes = $get_check_in_time->diffInRealMinutes($get_check_out_time);
                    $get_calculation=$user->user_tracker_calculation($convert_date->timestamp)->first();
                    if($get_calculation == null){
                        $update_attendance = $get_date->update([
                            'check_out_time' => Carbon::parse($request->check_out_time)->timestamp,
                        ]);
                        $calculation=TrackerCalculation::create([
                            'time_spent'=>$get_diff_minutes,
                            'user_id'       => $request->auth->id,
                            'date'      =>$convert_date->timestamp
                        ]);

                    }
                    if ($get_calculation != null){
                        $update_attendance = $get_date->update([
                            'check_out_time' => Carbon::parse($request->check_out_time)->timestamp,
                        ]);
                        $get_time=$get_calculation->time_spent;
                        $calculation=$get_calculation->update([
                            'time_spent' => $get_diff_minutes + $get_time
                        ]);
                    }

                    $attendances = TrackerAttendance::create([
                        'check_in_time'     => Carbon::parse($request->check_out_time)->startOfDay()->timestamp,
                        'user_id'           => $request->auth->id,
                        'date'              => Carbon::parse($request->check_out_time)->startOfDay()->timestamp,
                        'check_out_time'    => Carbon::parse($request->check_out_time)->timestamp,
                        ]);
                    $get_check_out_time_calculation=Carbon::parse($request->check_out_time);
                    $get_day_start_check_out=Carbon::parse($request->check_out_time)->startOfDay();
                    $diff_minutes = $get_day_start_check_out->diffInRealMinutes($get_check_out_time_calculation);
                            $calculation=TrackerCalculation::create([
                                'user_id'       => $request->auth->id,
                                'time_spent'=>$diff_minutes,
                                'date'      =>Carbon::parse($request->check_out_time)->startOfDay()->timestamp
                            ]);


            }
            return response()->json(['status' => 'Attendance Marked successful !']);

        }
        else {
            return response()->json(['status' => 'Attendance Marked unsuccessful ,Please Make sure your check In !']);

        }
    }

}
