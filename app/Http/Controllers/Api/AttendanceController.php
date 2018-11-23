<?php

namespace App\Http\Controllers\Api;

use App\Attendance;
use App\Inform;
use App\TimeTraker;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class AttendanceController extends Controller
{
    public function check_in_time(Request $request)
    {

        $this->validate($request, [
            'check_in_time' => 'required|date',
        ]);
        $check_in_time = Carbon::parse($request->check_in_time)->timestamp;
        $user = $request->auth;
//        $default_time = Carbon::parse($user->checkInTime)->addMinutes(30);
//        $attendance_time = Carbon::parse($request->check_in_time);
//        $compare_time = $attendance_time->gt($default_time);
//        if ($compare_time) {
//            $attendance = Carbon::parse($request->check_in_time);
//            $add_day = $attendance->endOfDay()->timestamp;
//            $status = $compare_time ? 'late' : 'check_in';
//            $inform = Inform::whereBetween('attendance_date', [$attendance->startOfDay()->timestamp, $add_day])->where('user_id', $request->auth->id)->first();
//        } else {
//            $inform = false;
//            $status = 'check_in';
//        }
//            if ($inform) {
//                if ($inform->inform_type == 'LEAVE') {
//
//                    return response()->json(['status'=>'You are on Leave Concern to HR !']);
//                }
//            }
            $attendances = TimeTraker::create([
                'check_in_time' => $check_in_time,
                'user_id' => $request->auth->id,
            ]);
            if ($attendances)
            {
                return response()->json(['status'=>'Attendance Marked successFully !']);

            }
            else{
                return response()->json(['status'=>'Attendance Marked unsuccessful please try again !']);

            }
    }
}
