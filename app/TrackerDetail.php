<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackerDetail extends Model
{
    protected $fillable=[
        'user_id',
        'tracker_status_id',
        'time',
        'status',
        'date',
        'tracker_attendance_id'
    ];
}
