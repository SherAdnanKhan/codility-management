<?php

//namespace App\Http\Controllers\Api;
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\TimeTraker;
use App\TrackerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;


class TimeTrakerController extends Controller
{
    public function imperativeMinutes(Request $request){


            $get_time_start_time_report = array_first($request->slots);
            $get_time_end_time_report   = last($request->slots);
            $start_report_time =$get_time_start_time_report['time'];

            $end_report_time = $get_time_end_time_report['time'];
            $get_slots=collect($request->slots);
            $date=Carbon::parse($get_time_end_time_report['time'])->startOfDay()->timestamp;
            $check_bits_off = $get_slots->filter(function ($value, $key) {
                return $value['status'] == false;
            });
            $check_bits_on = $get_slots->filter(function ($value, $key) {
                return $value['status'] == true;
            });
            $user=$request->auth;
            $user_imperative_minutes=$user->imperative_minutes;
            $user_screen_capture_time=$user->capture_duration;
            $get_minutes_user_screen=explode(":", $user_screen_capture_time);
            $whole_slots_count=count($request->slots);
            $time_calulation=$user->user_tracker_calculation($date)->first();
            if ($time_calulation !=null){
                $previous_time=$time_calulation->time_logged?$time_calulation->time_logged:0;
                $time_calulation->update([
                    'time_logged'=> $previous_time + $whole_slots_count
                ]);
            }
            $check_time=abs($whole_slots_count-$get_minutes_user_screen[0]);
            if ($whole_slots_count < $get_minutes_user_screen[0]) {
                //user who have less time from the screen capture time he off time tracker
                $count_of_on_bits = count($check_bits_on->all());
                if ($count_of_on_bits >= $user_imperative_minutes) {
                    //if user count bits greater then form the setting allowed

                    $marked_status = $user->user_tracker_status()->create([
                        'tracker_attendance'    => $request->tracker_id,
                        'status'                => 'ON',
                        'url'                   => $request->url ? $request->url : null,
                        'report_start_time'     => Carbon::parse($start_report_time)->timestamp,
                        'report_end_time'       => Carbon::parse($end_report_time)->timestamp,
                        'tracker_attendance_id' => $request->tracker_id ? $request->tracker_id : null,
                        'date'                  => $date,
                        'url_image_time'        => $request->capture_time ? Carbon::parse($request->capture_time)->timestamp : null,
                    ]);


                }

             else{
                 $marked_status = $user->user_tracker_status()->create([
                     'tracker_attendance' => $request->tracker_id,
                     'status' => 'DEFAULT',
                     'url'                   => $request->url ? $request->url : null,
                     'report_start_time'     => Carbon::parse($start_report_time)->timestamp,
                     'report_end_time'       => Carbon::parse($end_report_time)->timestamp,
                     'tracker_attendance_id' => $request->tracker_id ? $request->tracker_id : null,
                     'date'                  => $date,
                     'url_image_time'        => $request->capture_time ? Carbon::parse($request->capture_time)->timestamp : null,
                ]);
            }
                $minutes_less=abs($whole_slots_count-$get_minutes_user_screen[0]);
                $last_time=Carbon::parse($end_report_time);
                $detail_slots=collect($request->slots);
                foreach ($detail_slots as $slots_time){
                    $slots_detail=$marked_status->status_tracker_detail()->create([
                        'user_id'                   => $user->id,
                        'time'                      => Carbon::parse($slots_time['time']),
                        'status'                    => $slots_time['status'],
                        'date'                      => $date,
                        'tracker_attendance_id'     => $request->tracker_id ? $request->tracker_id : null,


                    ]);
                }
                while ($minutes_less > 0){
//                    $last_time=Carbon::parse($end_report_time);
                    $time=$last_time;
                    $slots_detail=$marked_status->status_tracker_detail()->create([
                        'user_id'                   => $user->id,
                        'time'                      => $time->addMinute(1),
                        'status'                    =>  'DEFAULT',
                        'date'                      => $date,
                        'tracker_attendance_id'     => $request->tracker_id ? $request->tracker_id : null,


                    ]);
                    $minutes_less --;
                }


            }else {


                $count_of_on_bits = count($check_bits_on->all());
                if ($count_of_on_bits >= $user_imperative_minutes) {
                    //if user count bits greater then form the setting allowed

                    //get the user tracker last attendance
                    $marked_status = $user->user_tracker_status()->create([
                        'tracker_attendance'    => $request->tracker_id,
                        'status'                => 'ON',
                        'url'                   => $request->url ? $request->url : null,
                        'report_start_time'     => Carbon::parse($start_report_time)->timestamp,
                        'report_end_time'       => Carbon::parse($end_report_time)->timestamp,
                        'tracker_attendance_id' => $request->tracker_id ? $request->tracker_id : null,
                        'date'                  => $date,
                        'url_image_time'        => $request->capture_time ? Carbon::parse($request->capture_time)->timestamp : null,
                    ]);


                } else {
                    $marked_status = $user->user_tracker_status()->create([
                        'tracker_attendance' => $request->tracker_id,
                        'status' => 'OFF',
                        'url'                   => $request->url ? $request->url : null,
                        'report_start_time'     => Carbon::parse($start_report_time)->timestamp,
                        'report_end_time'       => Carbon::parse($end_report_time)->timestamp,
                        'tracker_attendance_id' => $request->tracker_id ? $request->tracker_id : null,
                        'date'                  => $date,
                        'url_image_time'        => $request->capture_time ? Carbon::parse($request->capture_time)->timestamp : null,
                    ]);
                }

                foreach ($request->slots as $slots_time){
                    $slots_detail=$marked_status->status_tracker_detail()->create([
                        'user_id'                   => $user->id,
                        'time'                      => Carbon::parse($slots_time['time'])->timestamp,
                        'status'                    => $slots_time['status'],
                        'date'                      => $date,
                        'tracker_attendance_id'     => $request->tracker_id ? $request->tracker_id : null,


                    ]);
                }

            }

            return response()->json([
                'success' => 'Success fully tracked'
            ]);

    }
}
