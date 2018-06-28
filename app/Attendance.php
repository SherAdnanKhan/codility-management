<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable=[
        'check_in_time',
        'check_out_time',
        'break_interval',
        'time_spent',
        'user_id',
        'attendance_type',
        'leave_id',
        'leave_comment',
        'informed',
        'late_informed',
    ];
    public function getBreakIntervalAttribute($value){
        if($value == true){
        return date('H:i ',$value);}
    }
    public function getCheckInTimeAttribute($value){
        return date('m/d/Y h:i A',$value);
    }
    public function getTimeSpentAttribute($value){
        if ($value==true) {
            return ($value.' Hours');
        }
        else{
            return 'No Time Spent';
        }
        }
    public function getAttendanceTypeAttribute($value)
    {
        switch ($value)
            {
                case "check_in":
                    return 'On Time';
                    break;
                case "late":
                    return 'Late';
                    break;
                case "LeaveBySystem":
                    return 'Informed-Leave Marked System ';
                    break;
                case "AbsentBySystem":
                    return 'Absent Marked System';
                    break;
                case "LeaveByAdmin":
                    return 'Leave Marked Admin';
            case "inform":
                return 'Inform';
                default:
                    return 'No Status';
            }
    }
    public function getCheckOutTimeAttribute($value){
        if($value== true) {
            return date('m/d/Y h:i A', $value);
        }
        else
        {
            return false;
        }
    }
    public function getLateInformedAttribute($value){
        if($value== true) {
            return 'But LATE';
        }
        else
        {
            return false;
        }
    }
    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }
    public function leave(){
        return $this->hasOne('App\Leave','id','leave_id');
    }
    public function inform(){
        return $this->hasOne('App\Inform','id','leave_id');
    }
}
