<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    protected $fillable =[
        'start_time', 'end_time', 'working_hour', 'non_working_hour', 'monday', 'tuesday', 'wednesday',
        'thursday', 'friday', 'saturday','sunday',
    ];



    public function getStartTimeAttribute($value)
    {
     return date('h:i A',$value);
    }
    public function getEndTimeAttribute($value)
    {
        return date('h:i A',$value);
    }
    public function getWorkingHourAttribute($value)
    {
        return date('h:i',$value);
    }
    public function getNonWorkingHourAttribute($value)
    {
        return date('H:i',$value);
    }
    public static  function getAllowedDays()
    {
        $get_time  = TimeTable::whereId(1)->first();
        $check_day['monday']        = $get_time->monday;
        $check_day['tuesday']       = $get_time->tuesday;
        $check_day['wednesday']     = $get_time->wednesday;
        $check_day['thursday']      = $get_time->thursday;
        $check_day['friday']        = $get_time->friday;
        $check_day['saturday']      = $get_time->saturday;
        $check_day['sunday']        = $get_time->sunday;
        $time                       = array_count_values($check_day);
        return $time[1];
    }
}
