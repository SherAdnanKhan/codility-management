<?php

namespace App;

use Carbon\Carbon;
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
        return date('m/d/Y g:i A',$value);
    }
    public function getTimeSpentAttribute($value){
        if ($value==true) {
            return (date('H:i' ,mktime(0,$value)));
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
                return 'UnInformed late';
                break;
            case "LeaveBySystem":
                return 'Informed leave';
                break;
            case "AbsentBySystem":
                return 'Un-Informed leave';
                break;
            case "LeaveByAdmin":
                return 'Leave Marked By Admin';
            case "inform":
                return 'Informed';
            default:
                return 'No Status';

        }

    }
    public function getCheckOutTimeAttribute($value){
        if($value== true) {
            return date('m/d/Y g:i A', $value);
        }
        else
        {
            return false;
        }
    }
    public function getLateInformedAttribute($value){
        if($value == true) {
            return "Late Notify";
        }
        else
        {
            return false;
        }
    }
    public static function mktimecustom($date){
        $timestamp=strtotime($date);
        $time=Carbon::createFromTimestamp($timestamp)->format('H:i');
        $explode= explode(':',$time);

        if($explode[0] == '00' && $explode[1] != '00'){
            return $explode[1].'m';
        }elseif($explode[0] != '00' && $explode[1] == '00') {
            return $explode[0] .'h ';
        }elseif($explode[1] != '00' && $explode[0] != '00'){
            return $explode[0] .'h '.$explode[1].'m';
        }elseif ($explode[0] == '00' && $explode[1] == '00'){
            return ' ';
        }
    }
    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }
    public function leave(){
        return $this->hasOne('App\Leave','id','leave_id');
    }
    public function inform($start, $end){
        return $this->hasOne('App\Inform','user_id','user_id')->whereBetween('attendance_date',[$start, $end])->first();
    }
    public function tasks(){
        return $this->hasMany('App\Task','user_id','user_id');
    }
    public function task_report(){
        return $this->hasMany('App\Task','user_id','user_id')->whereBetween('date',[Carbon::yesterday()->timestamp, Carbon::now()->timestamp]);
    }
    public function task_friday($start,$end){
        return $this->hasMany('App\Task','user_id','user_id')->whereBetween('date',[$start, $end]);
    }
    public static function mktimesimple($date){
        $explode= explode(':',$date);
        if($explode[0] == '00' && $explode[1] != '00'){
            return $explode[1].'m';
        }elseif($explode[0] != '00' && $explode[1] == '00') {
            return $explode[0] .'h ';
        }elseif($explode[1] != '00' && $explode[0] != '00'){
            return $explode[0] .'h '.$explode[1].'m';
        }elseif ($explode[0] == '00' && $explode[1] == '00'){
            return ' ';
        }
    }

}
