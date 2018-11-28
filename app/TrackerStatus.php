<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackerStatus extends Model
{
    protected $fillable=[
        'user_id',
        'status',
        'url',
        'report_start_time',
        'report_end_time',
        'tracker_attendance_id',
        'date'
    ];

}