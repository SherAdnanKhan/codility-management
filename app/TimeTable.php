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
    public static function getAllowedDays()
    {
        $time_table  = TimeTable::first();
        $day['monday']        = $time_table->monday;
        $day['tuesday']       = $time_table->tuesday;
        $day['wednesday']     = $time_table->wednesday;
        $day['thursday']      = $time_table->thursday;
        $day['friday']        = $time_table->friday;
        $day['saturday']      = $time_table->saturday;
        $day['sunday']        = $time_table->sunday;
        $days_count = array('days_count'=>array_count_values($day)[1] , 'time_table'=> $time_table);
        return $days_count;
    }
}
