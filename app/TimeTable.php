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
}
